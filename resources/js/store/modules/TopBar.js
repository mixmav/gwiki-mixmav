export const TopBar = {
	namespaced: true,
	state: {
		link: '',
        isGettingContent: false,
		allowCache: true,
	},

	mutations: {
		updateIsgettingContent(state, value){
			state.isGettingContent = value;
		},

		updateLink(state, value){
			state.link = value;
		},

		addRandomLink(state, value){
			var links = [
							"https://en.wikipedia.org/wiki/Nuclear_physics",
							"https://en.wikipedia.org/wiki/Internet",
							"https://en.wikipedia.org/wiki/Bicycle",
							"https://en.wikipedia.org/wiki/Random_number_generation",
							"https://en.wikipedia.org/wiki/Computer",
							"https://en.wikipedia.org/wiki/Chocolate",
							"https://en.wikipedia.org/wiki/Andromeda_Galaxy",
							"https://en.wikipedia.org/wiki/Rubik's_Cube",
							"https://en.wikipedia.org/wiki/Black_hole",
							"https://en.wikipedia.org/wiki/Solar_System",
						];
			state.link = links[Math.floor(Math.random()*links.length)];
		},

		updateAllowCache(state, value){
			state.allowCache = value;
		}
	},

	actions: {
		updateIsgettingContent(context, value){
            context.commit('updateIsgettingContent', value);
		},
	}
}
