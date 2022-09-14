<template>
	<div class="top-bar-container">
		<div class="top-bar" :class="{scrolled: scrolled, 'has-formatted-content': hasFormattedContent}">
			<div class="input-container">
				<input type="text" ref="linkInput" placeholder="Enter a Wikipedia link" :value="link" @input="updateLink($event.target.value)" @keyup.enter="searchBtnClick" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">

				<div id="search-btn" @click="searchBtnClick">
					<i class="fa fa-search"></i>
				</div>
			</div>
		</div>

		<i class="toggle-settings-dialog fa fa-cogs" @click="toggleSettingsDialog(true)" :class="{scrolled: scrolled, 'has-formatted-content': hasFormattedContent}"></i>
		<div class="settings-sign-img"></div>

		<div class="content-loader" :class="{visible: isGettingContent}">
			<div class="content-loader-ball-conatiner">
				<label>&nbsp;<i class="fa fa-fighter-jet"></i></label>
				<label>&nbsp;<i class="fa fa-lightbulb"></i></label>
				<label>&nbsp;<i class="fa fa-hourglass-half"></i></label>
				<label>&nbsp;<i class="fa fa-motorcycle"></i></label>
				<label>&nbsp;<i class="fa fa-magnet"></i></label>
				<label>&nbsp;<i class="fa fa-volleyball-ball"></i></label>
			</div>
		</div>
	</div>
</template>

<script>
	import { mapState, mapGetters, mapActions, mapMutations } from 'vuex';
	import $ from 'jquery';
	var ajaxReq = {abort: function () {}};

	export default {
		mounted(){
			this.$refs.linkInput.focus();

			this.$root.$on('linkInputFocus', () => {
				this.$refs.linkInput.focus();
			});

			this.$root.$on('searchBtnClick', () => {
				this.searchBtnClick();
			});

			var link = this.getParameterByName('link');
			if (link !== null) {
				this.updateLink(link);
				this.searchBtnClick();
				window.history.replaceState({}, '', '/');
			}
		},

		computed: {
			...mapState('TopBar', [
				'isGettingContent',
				'link',
				'allowCache',
			]),

			...mapState('Content', [
				'formattedData',
			]),

			...mapState([
				'scrolled',
			]),

			...mapGetters([
				'hash',
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
			...mapMutations('TopBar', [
				'updateLink',
				'updateAllowCache',
			]),

			...mapActions('Settings', {
				toggleSettingsDialog: 'updateVisible',
			}),

			...mapActions('Alert', [
				'showAlert',
			]),

			...mapActions('Content', [
				'updateFormattedData',
			]),

			...mapActions('TopBar', {
				toggleIsGettingContent: 'updateIsgettingContent',
			}),

			searchBtnClick(){
				if (this.link != '') {
					if(/^\s*(?:https?:\/\/)?(?:www\.)?(?:[a-z]{1,6}?\.)?(m\.)?wikipedia\.org\/wiki\/[^\s]+\s*?$/i.test(this.link)){
						if(window.location.pathname != '/'){
							window.location = '/?link=' + this.link;
							return false;
						}

						if (this.isGettingContent) {
							return false;
						}

						ajaxReq.abort();
						var vThis = this;
						ajaxReq = $.ajax({
							url: '/process',
							type: 'POST',
							data: {
								link: vThis.link,
								hash: vThis.hash,
								allowCache: vThis.allowCache,
							},
							beforeSend(){
								vThis.toggleIsGettingContent(true);
							},
							complete(){
								setTimeout(() => {
									vThis.toggleIsGettingContent(false);
									vThis.$refs.linkInput.blur();
									vThis.updateAllowCache(true);
								}, 500)
							},

							error: function(){
								vThis.showAlert({
									message: "<i class='error fa fa-exclamation-triangle'></i>Aw Snap, something went wrong.<br><br>Try refreshing the page or <a class='a' target='_BLANK' href='/report'>report an issue</a>. Mmkay?",
									ref: vThis.$refs.linkInput
								});
							},
							success: function(response){
								var response = JSON.parse(response);
								setTimeout(() => {
									if (response.error) {
										vThis.showAlert({
											message: "<i class='error fa fa-exclamation-triangle'></i>" + response.heading + "<br><br>Try again. <a class='a' target='_BLANK' href='/report'>Report an issue</a> if you think this isn't you. Mmkay?",
											ref: vThis.$refs.linkInput
										});
									} else {
										vThis.updateFormattedData({
											formattedHeading: response.heading,
											formattedContent: response.content,
											cached: response.cached_at,
										});
									}
								}, 500)
							}
						});
					} else {
						this.showAlert({
							message: "That doesn't look like a valid Wikipedia link.<br><br>Click <a href='/report' target='_BLANK' class='a'>here</a> to report a bug <i class='fa fa-bug'></i>",
							ref: this.$refs.linkInput,
						});
					}
				} else {
					this.showAlert({
						message: "<i class='fa fa-paperclip'></i>&nbsp;Can't leave it blank.<br><br><i style='font-size: 1.5em' class='fa fa-frown'></i>",
						ref: this.$refs.linkInput,
					});
				}
			},

			getParameterByName(name){
				var url = window.location.href;
				var name = name.replace(/[\[\]]/g, "\\$&");
				var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)");
				var	results = regex.exec(url);
				if (!results) return null;
				if (!results[2]) return '';
				return decodeURIComponent(results[2].replace(/\+/g, " "));
			}
		}
	}
</script>

<style lang="scss">
	@import "../../sass/vue-sass/imports";

	.top-bar-container{
		.top-bar{
			background: $primary-color;
			padding: 1em {
				right: 2em;
			}
			top: 0;
			left: 0;
			width: 100%;
			z-index: $zIndex-top-bar;
			position: fixed;
			transition: all .35s;

			&.scrolled{
				@extend .z-depth-scrolled;
			}

			&.has-formatted-content{
				background: $has-formatted-content-color;
			}

			.input-container{
				height: 60px;
				position: relative;
				margin-left: 65px;
				& > *{
					height: 100%;
					display: inline-block;
					position: absolute;
				}
				#search-btn{
					background: rgba(white, 0.2);
					border-radius: 3px;
					border-top-right-radius: 0;
					border-bottom-right-radius: 0;
					cursor: pointer;
					width: 60px;
					transition: all .3s;
					@extend .no-select;
					i{
						color: rgba(white, 0.5);
						position: relative;
						transition: all .3s;
						transform: translate(-50%, -50%);
						top: 50%;
						left: 50%;
					}
				}

				input{
					width: calc(100% - 50px);
					max-width: 60em;
					left: 60px;
					padding: 1.5em {
						left: .5em;
					}
					border: none;
					border-radius: 3px;
					border-top-left-radius: 0;
					border-bottom-left-radius: 0;
					transition: all .3s;
					color: white;
					background: rgba(white, 0.2);
					font: {
						size: 1em;
					}
					&::placeholder {
						color: rgba(white, 0.5);
						font-size: inherit;
						transition: all .3s;
						opacity: 1;
					}

					&:-ms-input-placeholder {
						color: rgba(white, 0.5);
						ms-transition: all .3s;
					}

					&::-ms-input-placeholder {
						color: rgba(white, 0.5);
						ms-transition: all .3s;
					}

					&:focus{
						background: white;
						color: rgba(black, 0.7);
						&::placeholder {
							color: rgba(black, 0.7);
							opacity: 1;
						}

						&:-ms-input-placeholder {
							color: rgba(black, 0.7);
						}

						&::-ms-input-placeholder {
							color: rgba(black, 0.7);
						}
						&+#search-btn{
							background: white;
							i{
								color: rgba(black, 0.7);
							}
						}
					}
				}
			}
		}
		.toggle-settings-dialog{
			color: white;
			position: fixed;
			padding: 4em {
				left: 3em;
				right: 3em;
				bottom: 1em;
			}
			cursor: pointer;
			border-radius: 100%;
			z-index: $zIndex-settings-dialog-box-trigger;
			background: $primary-color;
			left: 50%;
			top: 1.5em;
			bottom: initial;
			&.has-formatted-content{
				background: $has-formatted-content-color;
			}
			font: {
				size: 1.1em;
			}
			transform: translate(-50%, 0);
			transition: all .25s;
			@extend .no-select;
			&.scrolled{
				@extend .z-depth-1;
			}
			&:hover{
				transform: translate(-50%, 5px);
				padding: {
					bottom: calc(1em + 5px);
				}
			}
		}

		.settings-sign-img{
			background: url("/images/settings-sign.png");
			background-repeat: no-repeat;
			background-size: 100% 100%;
			position: absolute;
			top: 140px;
			left: 50%;
			height: 60px;
			width: 190px;
			opacity: 0.8;
			transform: translate(-70%, 0);
			z-index: $zIndex-content;
			@extend .no-select;
		}

		.content-loader{
			margin-top: 220px;
			opacity: 0;
			@extend .no-select;
			margin-bottom: -25px;
			transition: all .3s ease 1s;
			overflow: hidden;
			.content-loader-ball-conatiner{
				label{
					@for $i from 1 through 6{
						$j: 600 - ($i*100);
						&:nth-child(#{$i}){
							animation: contentLoadAnim 2.5s $j#{ms} infinite ease-in-out;
						}
					}
				}
			}
			&.visible{
				margin-bottom: 20px;
				transition: all .3s ease 0s;
				opacity: 1;
			}
			.content-loader-ball-conatiner{
				transform: translateY(-50%);
				top: 0;
				left: 50%;
				position: relative;
				color: $primary-color !important;
				pointer-events: none;
				text-align: center;
				transform: translateX(-50%);
				label{
					font-size: 20px;
					opacity: 0;
					color: inherit;
					display: inline-block;
					animation: none;
					i{
						color: inherit;
						font: {
							size: inherit;
						}
					}
				}
			}
		}
	}

	@keyframes contentLoadAnim{
		0%{
			opacity: 0;
			transform: translateX(-300px);
		}
		33%{
			opacity: 1;
			transform: translateX(0px);
		}
		66%{
			opacity: 1;
			transform: translateX(0px);
		}
		100%{
			opacity: 0;
			transform: translateX(300px);
		}
	}



	@include media(680px){
		.top-bar-container{
			.settings-sign-img{
				height: 50px;
				width: 160px;
			}
		}
	}

	@include media(420px){
		.top-bar-container{
			.settings-sign-img{
				height: 40px;
				width: 120px;
			}
			.toggle-settings-dialog{
				font-size: 1em;
				top: 2em;
			}
		}
	}

	@include media(360px){
		.top-bar-container{
			.top-bar{
				.input-container{
					input{
						padding: {
							right: .2em !important;
							left: .2em !important;
						}
						font: {
							size: .9em !important;
						}
					}
				}
			}
		}
	}

	@include media(420px){
		.top-bar-container{
			.content-loader{
				margin-top: 200px;
			}
		}
	}
</style>
