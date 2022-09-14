import Vue from 'vue';
	import Vuex from 'vuex';

Vue.use(Vuex);

import { TopBar } from './modules/TopBar';
import { Alert } from './modules/Alert';
import { Settings } from './modules/Settings';
import { Content } from './modules/Content';

export const store = new Vuex.Store({
	strict: process.env.NODE_ENV !== 'production',

	modules: {
		TopBar: TopBar,
		Alert: Alert,
		Settings: Settings,
		Content: Content,
	},

	state: {
		scrolled: false,
		hash: 'none',
		fingerprint: 'none',
	},

	mutations: {
		updateScrolled(state, value){
			state.scrolled = value;
		},
		updateHash(state, value){
			state.hash = value;
		},
		updateFingerprint(state, value){
			state.fingerprint = value;
		}
	},

	getters: {
		hash(state){
			if (!state.hash) {
				return 'none';
			} else {
				return state.hash;
			}
		},
		fingerprint(state){
			if (!state.fingerprint) {
				return 'none';
			} else {
				return state.fingerprint;
			}
		}
	}
})
