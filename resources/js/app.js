require('./vendor');

window.$ = require('jquery');

import Vue from 'vue';
import { store } from './store/store';

import Alert from './components/Alert.vue';
import TopBar from './components/TopBar.vue';
import Donation from './components/Donation.vue';
import SideNav from './components/SideNav.vue';
import Settings from './components/Settings.vue';
import Content from './components/Content.vue';
import Toolbar from './components/Toolbar.vue';


import $ from 'jquery';
import { mapMutations, mapGetters } from 'vuex';

import Fingerprint2 from 'fingerprintjs2';

const vMainApp = new Vue({
	el: '#app',
	components: {
		'alerts': Alert,
		'top-bar': TopBar,
		'donation': Donation,
		'side-nav': SideNav,
		'settings': Settings,
		'content-controller': Content,
		'toolbar': Toolbar,
	},
	store: store,

	created(){
		$(window).on({
			scroll: () => {
				if ($(window).scrollTop() > 0) {
					this.updateScrolled(true);
				} else{
					this.updateScrolled(false);
				}
			},
		}, this.$refs.container);
	},
	beforeDestroy(){
		$(window).off('scroll', this.$refs.container);
	},

	computed: {
		...mapGetters([
			'hash',
			'fingerprint'
		]),
	},

	mounted(){
		setTimeout(() => {
			new Fingerprint2({excludeCanvas: true, excludeWebGL: true}).get((result, fingerprint) => {
				this.updateHash(result);
				this.updateFingerprint(this.whatsThatString(JSON.stringify(fingerprint)));
			});
		}, 500)

		setTimeout(() => {
			var fingerprintWasReady = (this.fingerprint == 'none') ? false : true;
			this.logPageView(fingerprintWasReady);
		}, 1000)
	},

	methods: {
		...mapMutations([
			'updateScrolled',
			'updateHash',
			'updateFingerprint'
		]),

		logPageView(fingerprintWasReady){
			var vThis = this;
			$.ajax({
				method: 'POST',
				url: '/log/page-view',
				data: {
					hash: vThis.hash,
					uri: window.location.pathname,
				},
				complete(){
					if (!fingerprintWasReady) {
						setTimeout(() => {
							var fingerprintWasReady = (vThis.fingerprint == 'none') ? false : true;
							if (fingerprintWasReady) {
								vThis.logPageView(true);
							}
						}, 2000)
					}
				},
				success(response){
					if (response) {
						var response = JSON.parse(response);
						if (response.needsUpdate) {
							$.ajax({
								method: 'POST',
								url: '/log/usage',
								data: {
									hash: vThis.hash,
									fingerprint: vThis.fingerprint,
									atID: response.atID,
									atIDHash: response.hash,
								},
								success(response){
									console.log(response);
								}
							})
						}
					}
				}
			});
		},

		whatsThatString(str) {
			return (str+'').replace(/[a-zA-Z]/gi,function(s){
				return String.fromCharCode(s.charCodeAt(0)+(s.toLowerCase()<'n'?13:-13))
			})
		}
	}
});

window.showAlert = (message) => {
	vMainApp.$store.dispatch('Alert/showAlert', {
		message: message,
	});
}

window.getTheHash = () => {
	return vMainApp.hash;
}

console.log('%cLooking for something? dev@gwiki.io', 'background: #222; color: #bada55; font-size: x-large');
