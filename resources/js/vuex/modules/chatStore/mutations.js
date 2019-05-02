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

  getUserContactsOnlineStatus(state, payload) {
    axios.get('/api/contacts-online-status?api_token=' + payload, {})
    .then(response => {
      let contactsArray = {};
      for(let i in response.data) {
        contactsArray[response.data[i]['id']] = response.data[i]['status'];
      }
      state.contactsOnlineStatus = contactsArray;
    });
  }
}

export default mutations;
