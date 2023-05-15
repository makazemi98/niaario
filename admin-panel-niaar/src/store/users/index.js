import { get } from '@/api/users/index';

export default {
    namespaced: true,
    state: {
        users: [],
    },
    getters: {
        getUsers: ({ users }) => users,
    },
    mutations: {
        SET_USERS(state, payload) {
            state.users = payload;
        },
    },
    actions: {
        async fetchUsers({ commit }, payload) {
          return await get({
            params: {
              page: 1,
            }
          })
          .then((response) => {
            console.log(response);
          })
          .catch((error) => {
            console.log(error);
          })
          /*
            let response;

            try {
                response = await new Promise((resolve, reject) => {
                    resolve(payload);
                    reject();
                });
            } catch (e) {
                throw new Error(e);
            }

            return response;
          */
        },
    },
};
