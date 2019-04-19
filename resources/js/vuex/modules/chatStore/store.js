import actions from './actions.js';
import mutations from './mutations.js';

const state = {
  currentContact: null,
  currentContactName: null,
  contactNotifications: []
};

const getters = {
};

const module = {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};

export default module;
