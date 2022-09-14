export const Settings = {
	namespaced: true,
	state: {
		visible: false,
		showSubheadings: true,
		colorSubheadings: true,
	},

	mutations: {
		updateVisible(state, value){
			state.visible = value;
		},

		toggleShowSubheadings(state, value){
			state.showSubheadings = !state.showSubheadings;
		},

		updateColorSubheadings(state, value){
			state.colorSubheadings = value;
		}
	},

	actions: {
		updateVisible(context, value){
			context.commit('updateVisible', value);
		},
	}
}
