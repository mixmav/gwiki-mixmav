<template>
	<div ref="container" class="side-nav-container">
		<div class="side-nav-trigger h-ripple" :class="{'close-side-nav': visible, 'has-formatted-content': hasFormattedContent}" @click="visible = !visible">
			<div class="component"></div>
			<div class="component"></div>
			<div class="component"></div>
		</div>

		<div class="side-nav" :class="{visible: visible}">
			<div class="top-bar">
				<a href="/">
					<img src="/images/branding/xhdpi.png?v=lOahIyaGhYt">
					<span v-once>Gwiki</span>
				</a>
			</div>
			<div class="links">
				<a href="/" class="nav-menu-item">
					<i class="fa fa-rocket"></i>
					<p>Home</p>
				</a>
				<a href="/report" class="nav-menu-item">
					<i class="fa fa-bug"></i>
					<p>Bugs and feedback</p>
				</a>
				<!-- <a href="#" class="nav-menu-item">
					<i class="fa fa-drafting-compass"></i>
					<p>Hire me</p>
				</a> -->
			</div>
		</div>
	</div>
</template>

<script>
	import { mapState } from 'vuex';
	import $ from 'jquery';

	export default{
		created(){
			$(window).on({
				keydown: event => {
					if (event.keyCode == 27) {
						this.visible = false;
					}
				},
				'touchstart mousedown': event => {
					if (event.target != this.$refs.container && !this.$refs.container.contains(event.target)) {
						this.visible = false;
					}
				}
			}, this.$refs.container);
		},

		beforeDestroy(){
			$(window).off('keydown', this.$refs.container);
		},
		data(){
			return{
				visible: false,
			}
		},
		computed: {
			...mapState('Content', [
				'formattedData'
			]),

			hasFormattedContent(){
				if (!this.formattedData.formattedContent) {
					return false;
				} else {
					return true;
				}
			},
		},
	}
</script>

<style lang="scss">
	@import "../../sass/vue-sass/imports";

	.side-nav-container{
		.side-nav{
			width: 15em;
			height: 100%;
			position: fixed;
			top: 0;
			left: 0;
			background: white;
			z-index: $zIndex-side-nav;
			overflow-y: auto;
			overflow-x: hidden;
			border-top-right-radius: 3px;
			border-bottom-right-radius: 3px;
			box-shadow: none;
			transition: all .25s ease-in;
			transform: translateX(-15em);
			&.visible{
				box-shadow: 0 16px 24px 2px rgba(0,0,0,0.14), 0 6px 30px 5px rgba(0,0,0,0.12), 0 8px 10px -5px rgba(0,0,0,0.2);
				transition: all .25s ease-out;
				transform: translateX(0);
			}
			& > div.top-bar{
				width: 100%;
				padding-bottom: 20px;
				& > a{
					display: inline-block;
					text-decoration: none;
					margin:{
						top: 70px;
						left: 20px;
					}
					color: $blue;
					font: {
						weight: 400;
						size: 1.6em;
					}
					& > img{
						width: 30px;
						height: 30px;
						position: relative;
						top: 3px;
					}
					& > span{
						position: relative;
						left: 15px;
						display: inline-block;
						color: rgba(black, 0.7);
						font: {
							weight: inherit;
							size: inherit;
						}
					}
				}
			}
			& > div.links{
				width: 100%;
				margin-top: 20px;
				& > a.nav-menu-item{
					padding: .5em{
						left: .8em;
					}
					min-height: 80px;
					display: block;
					cursor: pointer;
					position: relative;
					text-decoration: none;
					@extend .no-select;
					transition: all .3s;
					&:hover{
						background: darken(white, 5%);
						i{
							// transform-origin: center center;
							transform: scale(1.2, 1.2);
						}
					}
					&:active{
						background: darken(white, 10%);
					}
					p, i{
						display: inline-block;
						position: absolute;
						top: 30px;
						transition: all .15s;
					}
					i{
						font-size: 1.2em;
						color: $primary-color;
						left: 1.2em;
					}
					p{
						left: 4.8em;
						font:{
							size: 0.9em;
							weight: 700;
						}
						color: rgba(black, 0.8);
					}
				}
			}
		}

		.side-nav-trigger{
			cursor: pointer;
			position: fixed;
			z-index: $zIndex-side-nav-trigger;
			background: $primary-color;
			@extend .no-select;
			padding: 1.5em;
			border-radius: 100%;
			display: inline-block;
			top: 0px;
			transition: transform .25s ease-in-out, background .35s;
			transform: translate(5px, 14px) scale(0.75, 0.78);
			&.has-formatted-content{
				background: $has-formatted-content-color;
			}
			& > div.component{
				position: relative;
				left: 1px;
				width: 23px;
				height: 3px;
				border-radius: 100px;
				background: white;
				margin-top: 5px;
				transition: all .25s;
				&:nth-child(1){
					margin-top: 0;
				}
			}

			&.close-side-nav{
				transform: translate(10.9em, -5px) scale(0.5, 0.5);
				& > div.component{
					&:nth-child(2){
						opacity: 0;
					}

					&:nth-child(3){
						transform: translate(0px, -8px) rotate(45deg);
					}

					&:nth-child(1){
						transform: translate(0px, 8px) rotate(-45deg);
					}
				}
			}
		}
	}
</style>
