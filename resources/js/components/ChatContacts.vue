<template>
  <div class="mt-3">

    <h4>{{ user.portfolio }} Contacts</h4>

    <div class="form-group has-search">
      <span class="fa fa-search form-control-feedback"></span>
      <input v-model="contactSearch" class="form-control form-control-sm py-2" placeholder="Search">
    </div>

    <div
      style="font-size: 1.2em;"
      class="pl-1 contact-link"
      :class="[
        currentContact === contact.id ? 'contact-link-active': '',
        contactNotifications.includes(contact.id) && currentContact !== contact.id ? 'contact-blink' : ''
      ]"
      @click="setNewContact({id:contact.id, name:contact.name})"
      v-for="contact in filteredContactList">

      <i
        class="fa-circle"
        :class="[
          currentContact === contact.id ? 'fas': 'far',
          contactNotifications.includes(contact.id) && currentContact !== contact.id ? 'fas' : 'far'
        ]"
        ></i> {{ contact.name }}
        <div
          class="float-right pr-1"
          v-if="contact.status !== null">
          <i
            v-if="contact.status === 'online'"
            class="fas"
            :class="contact.active ? 'fa-grin-alt text-success' : 'fa-meh text-warning'">
          </i>
          <i
            v-else
            class="fas fa-minus-circle text-secondary">
          </i>
        </div>

        <div class="" style="font-size: .8em;">
          {{ contact.latest_message }}
        </div>

    </div>

  </div>
</template>

<script>

import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';

export default {

  props: ['user','contactNotificationsProp'],

  data() {
    return {
      contacts: null,
      onlineStatus: null,
      contactSearch: ''
    }
  },

  computed: {
    ...mapState('chatStore',
    [
      'currentContact',
      'currentContactName',
      'contactNotifications',
      'userContacts',
    ]),

    filteredContactList() {
      if(this.userContacts !== null) {
        return this.userContacts.filter(contact => {
          return contact.name.toLowerCase().includes(this.contactSearch.toLowerCase());
        });
      }
    }
  },

  methods: {
    ...mapMutations('chatStore',
      [
        'setNewContactInStore',
        'removeFromContactNotifications',
        'getUserContacts',
      ]
    ),

    setNewContact(contact) {
      this.setNewContactInStore(contact);
      if(this.contactNotifications.includes(contact.id)) {
        this.removeFromContactNotifications(contact.id);
      }

      axios.get('remove-contact-notification/' + contact.id).then(() => {
        axios.get('set-user-current-contact/' + this.currentContact);
      });
    }

  },

  mounted() {

    this.getUserContacts(this.user.api_token);

    setInterval(() => {
      this.getUserContacts(this.user.api_token);
    }, 30000);


    for(var i = 0; i < this.contactNotificationsProp.length; i++) {
      this.contactNotifications.push(this.contactNotificationsProp[i].contact_id);
    }

  },

  onIdle() {
    axios.get('user/' + this.user.id + '/idle');
  },

  onActive() {
    axios.get('user/' + this.user.id + '/active');
  }

}
</script>

<style lang="css">

  .contact-link {
    border-bottom: 1px dotted rgba(255,255,255,.2);
  }

  .contact-link:hover {
    background-color: rgba(0,0,0,.3);
    color: #d9d9d9;
    cursor: pointer;
  }

  .contact-link-active {
    background-color: rgba(0,0,0,.3);
    color: #d9d9d9;
  }

/* Bootstrap 4 text input with search icon */

  .has-search .form-control {
    padding-left: 1.8rem;
  }

  .has-search .form-control-feedback {
    position: absolute;
    z-index: 2;
    display: block;
    width: 1.8rem;
    height: 1.7rem;
    line-height: 1.8rem;
    text-align: center;
    pointer-events: none;
    color: #aaa;
  }

</style>
