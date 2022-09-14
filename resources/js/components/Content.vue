<template>
	<div class="content-controller-holder" :class="{disabled: isGettingContent}">
		<div :class="{visible: !hasFormattedContent}" class="placeholder-content content">
			<p class="donate-message">
				If this saved your time or blew your mind, please consider <span class="a" @click="showDonorboxDonation">donating <i class="fa fa-donate"></i></span>
			</p>

			<h1>
				<span>
					<span class="blue">Quickstart</span>
				</span>
			</h1>

			<p class="steps">
				1. Paste the link of the Wikipedia article you want to copy or <a class="a random" @click="addRandomLink"><i class="fa fa-random"></i>use a random link</a>
			</p>

			<p class="steps">
				2. Press enter or click the <span><i class="fa fa-search"></i></span> button to filter out all the <span class="bold blue">footnotes, superscripts</span> and <span class="bold blue">hyperlinks.</span>
			</p>

			<p class="steps">3. Copy the output, and breathe a sigh of relief.</p>

			<mailing-list></mailing-list>
			<p class="me-desc">Made with <i class="fa fa-heart"></i> by
				<a href="http://instagram.com/_u/mav.ew" class="a" target="_BLANK">@mav.ew</a>
			</p>
		</div>

		<div :class="{visible: hasFormattedContent}" class="formatted-content content">
			<transition name="scale-delay-cache">
				<button class="cached-alert btn full-width h-ripple" v-if="formattedData.cached" @click="reloadWithoutCache">
					<i class="fa fa-sync-alt" :class="{rotate: isGettingContent}"></i>
					Cached {{formattedData.cached}}
				</button>
			</transition>
			<h1>{{formattedData.formattedHeading}}</h1>
			<a style="margin-top: 4px; font-size: .8em;" href="https://docs.google.com/?action=newdoc" target="_BLANK" class="a"><i class="fa fa-edit"></i>Open Google Docs</a>
			<p style="color: #DA4167; margin-top: 20px; opacity: 0.6; font-size: .8em; font-weight: 700;"><i class="fa fa-charging-station"></i>&nbsp;&nbsp;You can edit the text</p>
			<div class="formatted-content-div" :class="{'color-subheadings': colorSubheadings, 'show-subheadings': showSubheadings}" contenteditable="" v-html="formattedData.formattedContent" id="clipboard-target"></div>
		</div>

		<button class="go-to-top icon-btn h-ripple" :class="{visible: scrolled}" @click="scrollToTop">
			<i class="fa fa-angle-up"></i>
		</button>
	</div>
</template>

<script>
	import { mapState, mapMutations } from 'vuex';
	import MailingList from './singles/MailingList';
	import $ from 'jquery';

	export default{
		components: {
			'mailing-list': MailingList,
		},

		computed: {
			...mapState('Content', [
				'formattedData',
			]),

			...mapState('TopBar', [
				'isGettingContent',
			]),

			...mapState('Settings', [
				'showSubheadings',
				'colorSubheadings'
			]),

			...mapState([
				'scrolled'
			]),

			hasFormattedContent(){
				if (!this.formattedData.formattedContent) {
					return false;
				} else {
					return true;
				}
			},
		},
		methods: {
			addRandomLink(){
				this.$store.commit('TopBar/addRandomLink');
				setTimeout(() => {
					this.$root.$emit('linkInputFocus');
				}, 200)
			},

			reloadWithoutCache(){
				this.$store.commit('TopBar/updateAllowCache', false);
				this.$root.$emit('searchBtnClick');
			},

			scrollToTop(){
				$('body, html').animate({scrollTop: 0}, 'fast');
			},

			showDonorboxDonation(){
				this.$root.$emit('showDonorboxDonation');
			}
		}
	}
</script>

<style lang="scss">
	@import "../../sass/vue-sass/imports";

	.content-controller-holder{
		width: 100%;
		height: auto;
		min-height: 100%;
		position: relative;

		overflow-wrap: break-word;
		-ms-word-break: break-all;
		word-break: break-all;
		word-break: break-word;
		-ms-hyphens: auto;
		-moz-hyphens: auto;
		-webkit-hyphens: auto;
		hyphens: auto;
		z-index: $zIndex-content;
		transition: opacity .2s;
		&.disabled{
			opacity: 0.6;
			pointer-events: none;
		}
		div.content{
			position: absolute;
			top: 0;
			left: 0;
			padding: 5.2em{
				top: 0;
			}
			transition: all .3s;
			width: 100%;

			&.visible{
				transition: all .3s;
				transform: translate(0, 0) scale(1, 1) rotate(0);
				opacity: 1;
				cursor: auto;
				pointer-events: auto;
			}
		}

		.placeholder-content{
			transform: translateY(200px);
			pointer-events: none;
			opacity: 0;
			p, h1{
				color: rgba(black, 0.7);
			}

			p.donate-message{
				transform-origin: 0% 100%;
				animation: translateYShake .3s;
				animation-iteration-count: 3;
				animation-delay: .3s;
				span{
					font: {
						weight: 800;
					}
					&.alert-color{
						color: $red;
					}
				}
			}

			h1{
				margin-top: 20px;
				font-size: 2em;
				span{
					&.blue{
						color: $primary-color;
						font-weight: 700;
					}
				}
				i{
					color: rgba(black, 0.7);
				}
			}
			h2{
				margin-top: 20px;
				color: rgba(black, 0.6);
				font: {
					weight: 400;
					size: 1.6em;
				}
				i{
					margin-left: 10px;
				}
			}
			h3{
				color: $blue;
				i{
					margin-right: 5px;
					position: relative;
				}
				font: {
					size: 1.5em;
					weight: 700;
				}
			}
			p{
				margin-top: 5px;
				line-height: 1.3em;
				span{
					&.bold{
						font-weight: 700;
					}

					&.blue{
						color: $blue;
					}

					&.red{
						color: $red;
					}

					&.coming-soon{
						display: inline-block;
						color: $blue;
						font: {
							weight: 700;
							size: 1em;
						}
						i{
							position: relative;
							margin-right: 5px;
						}

					}
				}
				&.steps{
					margin-top: 15px;
					font: {
						size: 1em;
						weight: 300;
					}
					color: rgba(black, 0.6);
					& > i{
						position: relative;
						left: 3px;
						font-size: 1em;
						color: $primary-color;
					}
					a{
						&.random{
							color: #D36582;
							*{
								vertical-align: middle;
							}
							i{
								margin-right: 5px;
							}
						}
					}
				}
			}

			span.shake-animation{
				display: inline-block;
				// animation: rotateShake .25s;
				// animation-iteration-count: 3;
				// animation-delay: .3s;
			}

			.me-desc{
				margin-top: 30px;
				color: rgba(black, 0.7);
				font: {
					size: .8em;
					weight: 700;
				}
				.a{
					font-size: .9em;
				}
				i{
					animation: heartPopAnim 1.5s linear infinite;
					animation-fill-mode: forwards;
				}
			}
		}

		.formatted-content{
			// opacity: 0;
			transform: translateX(-100%);
			cursor: default;
			pointer-events: none;
			padding-left: 52px !important;
			.cached-alert{
				border: solid 2px darken(#2081C3, 5%);
				box-shadow: none;
				margin-bottom: 10px;
				color: #2081C3;
				background: white;
				transition: all .15s;
				font-size: .8em;
				&:hover{
					background: #2081C3;
					color: white;
				}
				i {
					&.rotate{
						animation: rotateFull 2s linear infinite;
					}
				}
			}
			h1{
				font: {
					size: 2.2em;
				}
				margin: 0 {
					top: 5px;
				}
			}
			div.formatted-content-div{
				color: rgba(black, 0.8);
				p{
					font-size: 1.1em;
					line-height: 1.6em;
				}

				&.show-subheadings{
					@for $i from 1 through 6{
						h#{$i} {
							display: block;
						}
					}
				}

				&.color-subheadings{
					@for $i from 1 through 6{
						h#{$i} {
							color: $blue;
						}
					}
				}

				@for $i from 1 through 6{
					h#{$i} {
						margin-top: 30px;
						color: rgba(black, 0.8);
						display: none;
						font-size: 1em + 0.2 * (6 - $i);
					}
				}
			}
			.btn{
				&.copy-to-clipboard-btn{
					width: 167px;
					background: #607D8B;
					&.copied{
						background: $green;
						color: white;
					}
					&.error{
						background: $red;
						color: white;
					}
				}
				vertical-align: bottom;
			}
			.a{
				vertical-align: bottom;
				&.start-over{
					color: $blue;
					margin-left: 5px;
				}
				i{
					color: inherit;
					position: relative;
					bottom: 2px;
					margin-right: 3px;
				}
			}
		}

		.go-to-top{
			position: fixed;
			bottom: 10px;
			right: 10px;
			transform: scale(0, 0);
			@extend .no-select;

			&.visible{
				transform: scale(1, 1);
			}
		}
	}

	@include media(680px){
		.content-controller-holder{
			div.content{
				padding:{
					left: .8em;
					right: .8em;
				}
			}
		}
	}

	@include media(470px){
		.content-controller-holder{
			.formatted-content, .placeholder-content, .page-content{
				h1{
					font: {
						size: 1.6em;
					}
				}
				p{
					font: {
						size: 1em;
					}
				}
			}
		}
	}

	@include media(420px){
		.content-controller-holder{
			.content{
				padding: {
					top: 1em;
					left: .2em;
					right: .2em;
				}
			}
		}
	}


.scale-delay-cache-enter-active, .scale-delay-cache-leave-active{
	transition: all .3s 1s !important;
}

.scale-delay-cache-enter, .scale-delay-cache-leave-to{
	transition: all .3s 1s !important;
	transform: translateY(-100px);
	opacity: 0;
}

@keyframes heartPopAnim {
	0%, 100%{
		transform: scale(1, 1);
		color: #DA4167;
	} 20%{
		transform: scale(1.3, 1.3);
		color: magenta; 
	}
}
</style>
