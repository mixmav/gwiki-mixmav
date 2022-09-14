<template>
    <div ref="container" class="donorbox-donation-container" :class="{visible: visible}">
        <button class="close btn light h-ripple" @click="hide"><i class="fa fa-cat"></i></button>

        <div class="window" :class="{visible: !formVisible}">
            <h1 style="margin-top: 0"><i class="fa fa-balance-scale"></i>Why bother?</h1>
            <p>Gwiki is free-to-use. Your support helps keep projects like this alive. Gwiki will always be free.</p>

            <h1><i class="fa fa-credit-card"></i>Where does the money go?</h1>
            <p>To pay for the domain, hosting, and maintenance.</p>

            <h1><i class="fa fa-lock"></i>Is it secure?</h1>
            <p>
                Yes. All payments are processed via <a class="a" href="https://donorbox.org/" target="_BLANK">Donorbox</a> which uses <a class="a" href="https://stripe.com" target="_BLANK">Stripe</a>. The information is never shared with Gwiki.
            </p>

            <button class="donate btn full-width h-ripple" @click="formVisible = true"><i class="fa fa-donate"></i>Donate</button>
        </div>

        <div class="donation-form" :class="{visible: formVisible}">
            <h3>I've temporarily disabled donations, but I appreciate the gesture :)</h3>
            <!-- <iframe src="https://donorbox.org/embed/gwiki?hide_donation_meter=true" height="685px" width="100%" style="max-width:500px; min-width:310px; max-height:none!important" seamless="seamless" name="donorbox" frameborder="0" scrolling="no" allowpaymentrequest></iframe> -->
        </div>
    </div>
</template>

<script>
	import $ from 'jquery';

 	export default{
 		created(){
			$(window).on({
				keydown: event => {
					if (event.keyCode == 27) {
						this.hide()
					}
				},
			}, this.$refs.container);
		},

        mounted(){
            this.$root.$on('showDonorboxDonation', () => {
                this.visible = true;
            });
        },

		beforeDestroy(){
			$(window).off('keydown', this.$refs.container);
		},

		data(){
			return {
                visible: false,
                formVisible: false,
			}
		},

        methods: {
            hide(){
                this.visible = false;
                this.formVisible = false;
            }
        }
	}
</script>

<style lang="scss">
	@import "../../sass/vue-sass/imports";

    .donorbox-donation-container{
        position: fixed;
        width: 100%;
        height: 100%;
        background: white;
        top: 0;
        left: 0;
        z-index: $zIndex-donorbox-donation-container;
        transition: all .2s ease-in;
        transform: translateX(-100%);
        overflow: hidden;
        transition: all .35s ease-in;
        &.visible{
            transition: all .35s ease-out;
            transform: translateX(0);
        }

        button.close{
            position: absolute;
            top: 20px;
            right: 20px;
            border-radius: 100%;
            color: rgba(black, 0.6);
            background: white;
            font-size: 1.2em;
            box-shadow: none;
            border: solid 2px rgba(black, 0.6);
            z-index: 10001;
            padding: {
                right: .5em;
                left: 1.1em;
                top: .7em;
                bottom: .7em;
            }

            &:hover{
                transform: scale(0.9, 0.9);
            }
        }

        .window{
            margin-top: 40px;
            width: 100%;
            max-width: 400px;
            overflow: auto;
            display: inline-block;
            position: relative;
            z-index: 10000;
            text-align: left;
            padding: 1em {
                top: 1.5em;
            }
            left: 50%;
            transform: translate(-50%, -100%) scale(0, 1);
            transition: all .3s;
            &.visible{
                transform: translate(-50%, 0) scale(1, 1);
            }
            p{
                color: rgba(black, 0.7);
                margin-top: 5px;
                font: {
                    weight: 400;
                    size: .8em;
                }
                &.tip{
                    color: rgba(black, 0.6);
                }
            }
            h1{
                margin-top: 20px;
                margin-bottom: 0;
                display: inline-block;
                font: {
                    size: 1.5em;
                }
                color: $blue;
                i{
                    font-size: .85em;
                    position: relative;
                    bottom: 2px;
                    margin-right: 5px;
                }
            }
            button{
                margin-top: 20px;
                &.donate{
                    background: $green;
                }
            }
        }

        .donation-form{
            transition: all .2s;
            position: absolute;
            width: 100%;
            max-width: 400px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, 100%) scale(0, 1);
            text-align: center;
            &.visible{
                transform: translate(-50%, -50%) scale(1, 1);
            }
        }
    }

    @include media(700px){
        .donorbox-donation-container{
            transition: all .25s ease-in;

            &.visible{
                transition: all .25s ease-out;
            }
        }
    }
</style>
