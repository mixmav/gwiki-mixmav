<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;

use App\MailingListSubscriber;
use App\PageView;
use App\Issue;
use App\Fingerprint;

use Illuminate\Support\Facades\Mail;
use App\Mail\IssueReceived;
use App\Mail\EmailSubscriberAdded;

class HomeController extends Controller
{
    public function MailingListSubscriber(Request $request){
		$email = $request->email;

		$validator = Validator::make($request->all(), [
			'email' => 'required|email|max:255',
		]);

		if ($validator->fails()) {
			echo false;
			die();
		}

		$subscriber = new MailingListSubscriber;

		$subscriber->email = $email;
		$subscriber->IP = $request->ip();

        $hash_exists = (!empty($request->hash) && $request->hash != 'none') ? true : false;

        if ($hash_exists) {
			$fingerprint = Fingerprint::where('hash', '=', $request->hash);

			if ($fingerprint->exists()) {
				$subscriber->fingerprint_id = $fingerprint->first()->id;
			}
		}


		if (!MailingListSubscriber::where('email', '=', $email)->exists()) {
			$subscriber->save();
			Mail::to('manav.sg1@gmail.com')->queue(new EmailSubscriberAdded($subscriber));
		}

		echo true;
	}





    public function LogPageView(Request $request){
        $page_view = new PageView;

        $page_view->IP = $request->ip();
        $page_view->URI = $request->uri;

        $hash_exists = (!empty($request->hash) && $request->hash != 'none') ? true : false;

        if ($hash_exists) {
            if (Fingerprint::where('hash', '=', $request->hash)->exists()) {
                $fingerprint = Fingerprint::where('hash', '=', $request->hash)->first();
                $page_view->fingerprint_id = $fingerprint->id;
                $page_view->save();
            } else {
                $page_view->save();
                echo json_encode(['needsUpdate' => true, 'atID' => $page_view->id, 'hash' => \Hash::make($page_view->id)]);
            }
        }
    }


    public function LogFingerprint(Request $request){
        if (\Hash::check($request->atID, $request->atIDHash)) {
            $fingerprint_exists = (!empty($request->fingerprint) && $request->fingerprint != 'none') ? true : false;

            if ($fingerprint_exists) {
                if (!Fingerprint::where('hash', '=', $request->hash)->exists()) {
                    $fingerprint = new Fingerprint;

                    $fingerprint->hash = $request->hash;
                    $fingerprint->content = str_rot13($request->fingerprint);

                    $fingerprint->save();
                } else {
                    $fingerprint = Fingerprint::where('hash', '=', $request->hash)->first();
                }

                $page_view = PageView::find($request->atID);
                $page_view->fingerprint_id = $fingerprint->id;
                $page_view->save();
            }
        }
    }


    public function ReportIssue(Request $request){
        $messages = [
			'message.required' => 'Can\'t submit an empty issue.'
		];

		$validator = Validator::make(request()->all(), [
			'name' => 'max:100',
			'email' => 'required|email|max:255',
			'link' => 'max:500',
			'message' => 'required|max:2000'
		], $messages);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput();
		}

		$issue = new Issue;

		$issue->name = $request->name;
		$issue->email = $request->email;
		$issue->link = $request->link;
		$issue->message = $request->message;
		$issue->IP = $request->ip();

        $hash_exists = (!empty($request->hash) && $request->hash != 'none') ? true : false;

        if ($hash_exists) {
            $fingerprint = Fingerprint::where('hash', '=', $request->hash);

            if ($fingerprint->exists()) {
                $issue->fingerprint_id = $fingerprint->first()->id;
            }
        }


        $issue->save();

		Mail::to('manav.sg1@gmail.com')->queue(new IssueReceived($issue));
		session()->flash('issueSent', true);
		return back();
    }
}
