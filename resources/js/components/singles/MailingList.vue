<template>
	<div>
		<h3 style="margin-top: 35px"><i class="fa fa-mail-bulk"></i>Oh, and...</h3>
		<p>Join the mailing list for feature updates.<br>
			I <span class="bold">hate</span> spam.
			And you'll love the new stuff I'm working on.
			<span class="coming-soon"><i class="fa fa-bolt"></i>Coming soon</span>
		</p>
		<form @submit.stop.prevent="storeEmail" class="mailing-list-container">
			<transition name="scale">
				<input class="text-input full-width" v-show="!sent" v-model="email" placeholder="Your Email" name="email" type="email" required="true">
			</transition>

			<transition name="scale">
				<button v-show="!sent" class="btn full-width h-ripple" :class="{disabled: disabled}" type="submit"><i class="fa fa-bell" aria-hidden="true"></i>Subscribe</button>
			</transition>

			<transition name="scale-delay">
				<p v-show="sent" class="p success"><i class="fa fa-truck-moving" aria-hidden="true"></i>Thanks for signing up.</p>
			</transition>

			<transition name="scale">
				<p v-show="error" class="p error"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{email}} couldn't be added. Try again?</p>
			</transition>
		</form>
	</div>
</template>

<style lang="scss">
	@import "../../../sass/vue-sass/imports";

	.mailing-list-container{
		margin-top: 10px;
		display: inline-block;
		width: 100%;
		max-width: 300px;

		.btn{
			margin-top: 15px;
			i{
				vertical-align: top;
			}
		}
		.p{
			font: {
				size: 1em;
				weight: 800;
			}
			i{
				margin-right: 5px;
			}
			&.success{
				color: $primary-color !important;
			}
			&.error{
				color: #D36582 !important;
			}
		}

	    .scale-enter-active, .scale-leave-active{
		transition: all .25s cubic-bezier(0.4, 0.0, 0.2, 1);
	    }
	    .scale-enter, .scale-leave-to{
	    	transform: scale(0, 0);
	    }


	    .scale-delay-enter-active, .scale-delay-leave-active{
	    	transition: all .25s cubic-bezier(0.4, 0.0, 0.2, 1);
	    	transition-delay: .25s;
	    }
	    .scale-delay-enter, .scale-delay-leave-to{
	    	transform: scale(0, 0);
	    }
	}
</style>

<script>
	import $ from 'jquery';
    import { mapActions, mapGetters } from 'vuex';

	export default {
		data(){
			return {
				email: '',
				sent: false,
				disabled: false,
				error: false,
			}
		},
		methods: {
            ...mapActions('Alert', [
                'showAlert',
            ]),

			...mapGetters([
				'hash',
			]),

			storeEmail(){
				this.disabled = true;
				var self = this;

				$.ajax({
					method: 'POST',
					url: '/mailing-list/subscribe',
					data: {
						email: self.email,
						hash: self.hash,
					},

					error: function(){
						self.showAlert({
                            message: "Sorry, can\'t reach the servers. <br><br><i style='font-size: 1.5em' class='fa fa-frown blue'></i>"
                        });

						self.disabled = false;
					},

					success: function(response){
						if (response) {
							self.sent = true;
							self.error = false;
						} else {
							self.sent = false;
							self.error = true;
						}
					},

					complete: function(){
						self.disabled = false;
					}
				});
			}
		}
	};
</script>
