<template>
	<div ref="container" class="settings-cover-window" :class="{visible: visible}">
		<div class="dialog-box">
			<div class="content">
				<h1>Customize the output</h1>
				<checkbox @input="toggleShowSubheadings" checked="true" label='<i class="fa fa-clipboard-list"></i>Include subheadings'></checkbox>
				<p class="tip" style="margin-top: -10px">This option divides the output into sections, the way they appear on Wikipedia.</p>

				<div class="change-color" :class="{enabled: showSubheadings}">
					<h2 class="feature"><i class="fa fa-globe"></i>Color subheadings</h2>
					<div class="colors">
						<div class="blue" @click="updateColorSubheadings(true)">
							<div class="cover" :class="{visible: colorSubheadings}">
								<i class="fa fa-check"></i>
							</div>
						</div>
						<div class="black" @click="updateColorSubheadings(false)">
							<div class="cover" :class="{visible: !colorSubheadings}">
								<i class="fa fa-check"></i>
							</div>
						</div>
					</div>
					<p class="tip">If subheadings are enabled, you can use this to choose whether or not they're colored. Makes 'em stand out.. Ja feel?</p>
				</div>

				<!-- <h2 class="coming-soon"><i class="fa fa-parachute-box"></i>Coming soon</h2>
				<div class="md-checkbox disabled">
					<input id="md-checkbox-woah-2" type="checkbox" disabled="">
					<label for="md-checkbox-woah-2" style="opacity: .6;"><i class="fa fa-vial"></i>Change sentence structure</label>
				</div>
				<p class="tip">This option uses Artificial Intelligence to manipulate sentence and language structure in a way that makes it harder to pinpoint the source.</p> -->

				<button class="btn h-ripple full-width" @click="toggleVisible(false)"><i class="fa fa-gavel"></i>Close</button>
			</div>
		</div>
	</div>
</template>

<script>
	import { mapState, mapActions, mapMutations } from 'vuex';
	import $ from 'jquery';
	import Checkbox from './elements/Checkbox';

 	export default{
		components: {
			'checkbox':	Checkbox,
		},

 		created(){
			$(window).on({
				keydown: event => {
					if (event.keyCode == 27) {
						this.toggleVisible(false);
					}
				},
			}, this.$refs.container);
		},

		beforeDestroy(){
			$(window).off('keydown', this.$refs.container);
		},
		data(){
			return {
			}
		},

		computed: {
			...mapState('Settings', [
				'visible',
				'showSubheadings',
				'colorSubheadings',
			]),
		},

		methods: {
			...mapActions('Settings', {
				toggleVisible: 'updateVisible'
			}),

			...mapMutations('Settings', [
				'toggleShowSubheadings',
				'updateColorSubheadings',
			]),
		}
	}
</script>

<style lang="scss">
	@import "../../sass/vue-sass/imports";

	.settings-cover-window{
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(white, 0.93);
		z-index: $zIndex-settings-dialog-box;
		transform: translateX(-100%);
		text-align: center;
		padding: {
			top: 0em;
			bottom: 2em;
		}
		overflow: auto;
		transition: all .35s ease-in;
		&.visible{
			transition: all .35s ease-out;
			transform: translateX(0);
		}
		.dialog-box{
			width: 100%;
			max-width: 400px;
			overflow: auto;
			display: inline-block;
			position: relative;
			text-align: left;
			padding: 1em {
				top: 1.5em;
			}
			.content{
				.change-color{
					opacity: .6;
					pointer-events: none;
					@extend .no-select;
					transition: all .25s;
					margin-top: 30px;
					h2{
						color: rgba(black, 0.7) !important;
						transition: all .25s;
					}
					&.enabled{
						opacity: 1;
						pointer-events: auto;
						h2{
							color: $blue !important;
						}
						.colors{
							margin-top: 10px;
							.blue, .black{
								width: 50px;
								height: 50px;
								display: inline-block;
								border-radius: 100%;
								background: $blue;
								cursor: pointer;
							}
							.black{
								background: rgba(black, 0.7);
							}
						}
					}
					.colors{
						margin-top: 10px;
						.blue, .black{
							width: 50px;
							height: 50px;
							display: inline-block;
							border-radius: 100%;
							background: rgba(black, 0.6);
							cursor: pointer;
							transition: all .25s;
							.cover{
								background: rgba(white, 0.2);
								width: 100%;
								height: 100%;
								transition: all .2s;
								opacity: 0;
								&.visible{
									opacity: 1;
								}
								i{
									color: white;
									position: relative;
									top: 50%;
									left: 50%;
									transform: translate(-50%, -50%);
								}
							}
						}
						.black{
							margin-left: 10px;
						}
					}
					.tip{
						margin-top: 10px;
					}
				}
				p{
					color: rgba(black, 0.7);
					font: {
						weight: 400;
						size: .8em;
					}
					&.tip{
						color: rgba(black, 0.6);
					}
				}
				h1{
					font: {
						size: 1.5em;
					}
				}
				h2{
					font: {
						size: 1.2em;
						weight: 700;
					}
					i{
						margin-right: 5px;
						position: relative;
						bottom: 1px;
					}
					&.feature{
						color: $blue;
						margin-top: 25px;
					}
					&.coming-soon{
						margin-top: 45px;
						margin-bottom: -8px;
						color: $red;
					}
				}
				button{
					margin-top: 20px;
				}
			}
		}
	}

	@include media(700px){
		.settings-cover-window{
			transition: all .25s ease-in;

			&.visible{
				transition: all .25s ease-out;
			}
		}
	}
</style>
