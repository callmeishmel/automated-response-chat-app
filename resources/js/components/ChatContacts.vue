<template>
  <div class="mt-3">

    <h4>{{ user.portfolio }} Contacts</h4>

    <div
      class="pl-1 contact-link"
      :class="[
        currentContact === contact.id ? 'contact-link-active': '',
        contactNotifications.includes(contact.id) && currentContact !== contact.id ? 'contact-blink' : ''
      ]"
      @click="setNewContact({id:contact.id, name:contact.name})"
      v-for="contact in userContacts">
      <i
        class="fa-circle"
        :class="[
          currentContact === contact.id ? 'fas': 'far',
          contactNotifications.includes(contact.id) && currentContact !== contact.id ? 'fas' : 'far'
        ]"
        ></i> {{ contact.name }}
        <div
          class="float-right pr-1"
          style="opacity: .6;"
          v-if="contact.status !== null">
          <i
            v-if="contact.status === 'online'"
            class="fas fa-user-circle text-light">
          </i>
          <i
            v-else
            class="fas fa-minus-circle text-secondary">
          </i>
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
      onlineStatus: null
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

      axios.get('remove-contact-notification/' + contact.id).then(() => {});
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

</style>
