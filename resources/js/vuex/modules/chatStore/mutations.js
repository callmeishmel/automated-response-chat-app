const mutations = {
  setNewContactInStore(state, payload) {
    state.currentContact = payload;
  },

  addToContactNotifications(state, payload) {
    if(state.currentContact !== payload && !state.contactNotifications.includes(payload)) {
      state.contactNotifications.push(payload);
    }
  },

  removeFromContactNotifications(state, payload) {
    let contactIndex = state.contactNotifications.indexOf(payload);
    state.contactNotifications.splice(contactIndex,1);
  }
}

export default mutations;
