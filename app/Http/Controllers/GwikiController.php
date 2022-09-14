<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;
use App\WikipediaCache;
use App\Fingerprint;

use Carbon\Carbon;

class GwikiController extends Controller
{
	public function Process(){
		$link = $this->validate_link( request('link') );
		// $link = "https://wikipedia.org/wiki/Star_Wars";
		$wikipedia_cache = WikipediaCache::where('link', '=', $link);

		if($wikipedia_cache->exists() && request('allowCache') === "true"){
			$wikipedia_cache = $wikipedia_cache->first();

			if (Carbon::now()->diffInDays($wikipedia_cache->cached_at) < 30) {
				$response = array();
				$response['error'] = false;
				$response['heading'] = $wikipedia_cache->heading;
				$response['content'] = $wikipedia_cache->content;
				$response['cached_at'] = Carbon::parse($wikipedia_cache->cached_at)->diffForHumans();

				$wikipedia_cache->count = $wikipedia_cache->count + 1;
				$wikipedia_cache->save();

				$this->log($link);

				echo json_encode($response);
				exit();
			}
		}

		// TITLE
		preg_match('@/wiki/(?P<title>.*)$@i', $link, $matches_title);

		// LANGUAGE
		preg_match('@https://(?P<lang>.*?).?wikipedia.org@i', $link, $matches_language);

		if (isset($matches_language['lang']) && $matches_language['lang'] != "") {
			$api_link = "https://" . $matches_language['lang'] . ".wikipedia.org/w/api.php?format=json&action=query&prop=extracts&redirects=true&titles=" . $matches_title['title'];
		} else {
			$api_link = "https://wikipedia.org/w/api.php?format=json&action=query&prop=extracts&redirects=true&titles=" . $matches_title['title'];
		}

		$data = file_get_contents($api_link);
		$data = json_decode($data, true);
		@$data = array_values($data['query']['pages'])[0]['extract'];

		if (is_null($data) || $data == "") {
			$this->error("Not a valid Wikipedia link (no such article)");
		}

		$data = mb_convert_encoding($data, 'HTML-ENTITIES', "UTF-8");

		$title = str_ireplace('_', ' ', $matches_title['title']);
		$title = rawurldecode($title);
		// $title = ucfirst($title);

		$response = array();
		$response['error'] = false;
		$response['heading'] = $title;
		$response['content'] = "";

		$domDocument = new \DomDocument();
		@$domDocument->loadHTML($data);

		if (is_null($domDocument) || !isset($domDocument)) {
			$this->error("Couldn't parse the page.");
		}

		$xpath = new \DOMXpath($domDocument);

		$tags = $xpath->query('//p | //h1 | //h2 | //h3 | //h4 | //h5 | //h6');

		foreach ($tags as $node) {
			if (isset($node->nodeValue) && !is_null($node->nodeValue) && $node->nodeValue != "") {
				if (preg_match('@(h[1-6])@i', $node->tagName, $matched_tag)) {
					$matched_tag = $matched_tag[0];
					$response['content'] .= "<$matched_tag>" . $node->nodeValue . "</$matched_tag>";
				} else {
					$response['content'] .= "<p>$node->nodeValue</p>";
				}
			}
		}

		// Log
		$this->log($link);

		//Cache
		$wikipedia_cache = WikipediaCache::where('link', '=', $link);
		if($wikipedia_cache->exists()){
			$wikipedia_cache = $wikipedia_cache->first();
			$wikipedia_cache->count = $wikipedia_cache->count + 1;
		} else {
			$wikipedia_cache = new WikipediaCache;
			$wikipedia_cache->link = $link;
			$wikipedia_cache->count = 1;
		}

		$wikipedia_cache->heading = $response['heading'];
		$wikipedia_cache->content = $response['content'];
		$wikipedia_cache->cached_at = Carbon::now();
		$wikipedia_cache->save();


		echo json_encode($response);
	}

	public function error($message = "Aw, Snap! Something went wrong."){
		$response['error'] = true;
		$response['heading'] = $message;
		$response['content'] = "";
		echo json_encode($response);
		exit();
	}

	public function log($link){
		$log = new Log;
		$log->IP = request()->ip();
		$log->link = $link;

		$hash_exists = (!empty(request('hash')) && request('hash') != 'none') ? true : false;

		if ($hash_exists) {
			$fingerprint = Fingerprint::where('hash', '=', request('hash'));

			if ($fingerprint->exists()) {
				$log->fingerprint_id = $fingerprint->first()->id;
			}
		}

		$log->save();
	}

	public function validate_link($link){
		if (!preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:[a-z\-]{1,}?\.)?(m\.)?wikipedia\.org\/wiki\/.+$/i', $link)) {
			$this->error("Not a valid Wikipedia link (invalid format).");
		}

		preg_match('/^(?P<https>https?:\/\/)?(?:www\.)?(?P<lang>[a-z\-]{1,}?\.)?(?:m\.)?(?P<link>.*)$/i', $link, $validate_link_match);

		if ($validate_link_match['https'] == "") {
			$validate_link_match['https'] = "https://";
		}

		if ($validate_link_match['lang'] == "m.") {
			$validate_link_match['lang'] = "";
		}

		$link = $validate_link_match['https'] . $validate_link_match['lang'] . $validate_link_match['link'];

		return $link;
	}
}
