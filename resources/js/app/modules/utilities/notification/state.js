import $axios from '../../../plugins/axios'
import Echo from 'laravel-echo'
import io from 'socket.io-client'

const state = () => ({
    echo: null,
    isConnected: null,
    notification: null,
    collection: [],
})

const mutations = {
    //MEMASUKKAN DATA KE STATE COLLECTION
    ASSIGN_DATA(state, payload) {
        state.collection = payload
    },
    //MEMASUKKAN DATA KE STATE COLLECTION
    ASSIGN_NOTIFICATION(state, payload) {
        state.notification = payload
    },
    //MEMASUKKAN DATA KE STATE COLLECTION
    ADD_DATA(state, payload) {
        state.collection.data.push(payload)
        //Object.assign(state.collection.data, payload);
    },
    //MEMASUKKAN DATA KE STATE COLLECTION
    ASSIGN_CONNECTION_STATUS(state, payload) {
        state.isConnected = payload
    },
}

const actions = {
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA DARI SERVER
    load({ commit }) {
        return new Promise((resolve) => {
            $axios.get(`/notifications`)
            .then((response) => {
                //SIMPAN DATA KE STATE MELALUI MUTATIONS
                commit('ASSIGN_DATA', response.data)
                resolve(response.data)
            })
        })
    },
    /** Connect Echo **/
    connect({ state, rootState  }, payload) {
        if (!state.echo) {
            state.echo = new Echo({
                broadcaster: 'socket.io',
                host: `${window.location.hostname}:${window.laravel.echo.port}`,
                auth: {
                    headers: {
                        Authorization: 'Bearer ' + rootState.utilities.auth.authenticated.api_token
                    }
                }
            })
        }

        state.echo.connector.socket.on('connect', function (event) {
            console.log(`%cSocket connected`, 'color:green; font-weight:700;');
            state.isConnected = true
        });
        state.echo.connector.socket.on('disconnect', function () {
            console.log(`%cSocket disconnected`, 'color:red; font-weight:700;');
            state.isConnected = false
        });
        state.echo.connector.socket.on('reconnecting', function (attemptNumber) {
            console.log(`%cSocket reconnecting attempt ${attemptNumber}`, 'color:orange; font-weight:700;');
        });
    },
    /** Disconnect Echo **/
    disconnect({ state  }) {
        if(!state.echo){
            return state.echo.disconnect()
        }
    },
    /** Bind Channels **/
    bindChannels({ commit, state, rootState  }) {
        state.echo.private(`App.Models.User.${rootState.utilities.auth.authenticated.id}`)
            .notification((data) => {
                commit('ASSIGN_NOTIFICATION', data);
                commit('ADD_DATA', {'data':data});
            });
    },
    /** Bind Channels **/
    bindChatChannels({ state  }) {
        state.echo.join(`chat-room`)
            .here((users) => {
                this.userInRoom = users;
            })
            .leaving((userLeaving) => {
                // console.log(userLeaving.id);
                this.userInRoom = this.userInRoom.filter((user) => {
                    user != userLeaving
                })
            })
            .joining((userJoining) => {
                // console.log(userJoining);
                this.userInRoom.push(userJoining);
                // console.log(this.userInRoom);
            })
            .listen('MessagePosted', (eve) => {
                console.log(eve.message)
                /*this.messages.push({
                    message : eve.message.message,
                    user : eve.user
                })*/
            });
    },
}

export default {
    namespaced: true,
    state,
    actions,
    mutations
}