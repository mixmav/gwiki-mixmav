export const Alert = {
	namespaced: true,
	state: {
        visible: false,
        message: '',
        ref: null,
	},

	mutations: {
		updateVisible(state, value){
			state.visible = value;
		},

        updateMessage(state, value){
            state.message = value;
        },

        updateRef(state, value){
            state.ref = value;
        }
	},

	actions: {
        updateVisible(context, value){
            context.commit('updateVisible', value);

            if (value) {
                if (Boolean(this.state.Alert.ref)) {
                    this.state.Alert.ref.blur();
                }
            } else {
                setTimeout(() => {
                    if (Boolean(this.state.Alert.ref)) {
                        this.state.Alert.ref.focus();
                    }
                    context.commit('updateRef', null);
                }, 200);
            }
        },

		showAlert(context, value){
            context.commit('updateMessage', value.message);
            context.commit('updateRef', value.ref);
			context.dispatch('updateVisible', true);
		},
	}
}
