<template>
  <div class="mt-3">

    <h4>{{ user.portfolio }} Contacts</h4>

    <div
      class="pl-1 contact-link"
      :class="[
        currentContact === contact.id ? 'contact-link-active': '',
        contactNotifications.includes(contact.id) && currentContact !== contact.id ? 'contact-blink' : ''
      ]"
      @click="setNewContact(contact.id)"
      v-for="contact in contacts">
      <i
        class="fa-circle"
        :class="[
          currentContact === contact.id ? 'fas': 'far',
          contactNotifications.includes(contact.id) && currentContact !== contact.id ? 'fas' : 'far'
        ]"
        ></i> {{ contact.name }} ({{ contact.position }})
    </div>

  </div>
</template>

<script>

import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';

export default {

  props: ['user','contactNotificationsProp'],

  data() {
    return {
      contacts: null
    }
  },

  computed: {
    ...mapState('chatStore',
    [
      'currentContact',
      'contactNotifications'
    ]),
  },

  methods: {
    ...mapMutations('chatStore',
      [
        'setNewContactInStore',
        'removeFromContactNotifications'
      ]
    ),

    getUserContacts() {
      axios.get('/user-contacts').then(response => {

        this.contacts = response.data;
      });
    },

    setNewContact(contactId) {
      this.setNewContactInStore(contactId);
      if(this.contactNotifications.includes(contactId)) {
        this.removeFromContactNotifications(contactId);
      }

      axios.get('remove-contact-notification/' + contactId).then(() => {});
    }
  },

  mounted() {
    this.getUserContacts();

    for(var i = 0; i < this.contactNotificationsProp.length; i++) {
      this.contactNotifications.push(this.contactNotificationsProp[i].contact_id);
    }

  }

}
</script>

<style lang="css">
  .contact-link {

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

  /* Contact notification animation */

  .contact-blink {
   -webkit-animation: CONTACT-BLINK 1s infinite; /* Safari 4+ */
    -moz-animation:    CONTACT-BLINK 1s infinite; /* Fx 5+ */
    -o-animation:      CONTACT-BLINK 1s infinite; /* Opera 12+ */
    animation:         CONTACT-BLINK 1s infinite; /* IE 10+, Fx 29+ */
  }

  @-webkit-keyframes CONTACT-BLINK {
    0%, 49% {
        background-color: rgba(255,0,0,.3);
    }
    50%, 100% {
        background-color: rgba(255,0,0,.4);
    }
  }
</style>
