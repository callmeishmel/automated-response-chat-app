const mutations = {
  setNewContactInStore(state, payload) {
    state.currentContact = payload.id;
    state.currentContactName = payload.name;
  },

  addToContactNotifications(state, payload) {
    if(state.currentContact !== payload && !state.contactNotifications.includes(payload)) {
      state.contactNotifications.push(payload);
    }
  },

  removeFromContactNotifications(state, payload) {
    let contactIndex = state.contactNotifications.indexOf(payload);
    state.contactNotifications.splice(contactIndex,1);
  },

  getUserContacts(state, payload) {
    axios.get('/api/get-contacts?api_token=' + payload, {})
    .then(response => {
      state.userContacts = response.data;
    });
  }
}

export default mutations;
