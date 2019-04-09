import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

//Separate Module States
import chatStore from './modules/chatStore/store';

export default new Vuex.Store({
    modules: {
      chatStore: chatStore
    }
})
