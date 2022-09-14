export const Content = {
	namespaced: true,
	state: {
		formattedData: {
			formattedHeading: null,
			formattedContent: null,
			cached: null,
		}
	},

	mutations: {
		updateFormattedData(state, value){
			for (var key in value) {
				state.formattedData[key] = value[key];
			}
		},
	},

	actions: {
		updateFormattedData(context, value){
			context.commit('updateFormattedData', value);
		},
	}
}
