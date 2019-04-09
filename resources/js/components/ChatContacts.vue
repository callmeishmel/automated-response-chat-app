<template>
  <div class="mt-3">

    <h4>{{ user.portfolio }} Contacts</h4>

    <div
      class="pl-1 contact-link"
      :class="currentContact === contact.id ? 'contact-link-active': ''"
      @click="setNewContact(contact.id)"
      v-for="contact in contacts">
      <i class="far fa-circle"></i> {{ contact.name }} ({{ contact.position }})
    </div>

  </div>
</template>

<script>

import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';

export default {

  props: ['user'],

  data() {
    return {
      contacts: null
    }
  },

  computed: {
    ...mapState('chatStore',
    [
      'currentContact'
    ]),
  },

  methods: {
    ...mapMutations('chatStore',
      [
        'setNewContactInStore'
      ]
    ),

    getUserContacts() {
      axios.get('/user-contacts').then(response => {

        this.contacts = response.data;
      });
    },

    setNewContact(contactId) {
      this.setNewContactInStore(contactId);
    }
  },

  mounted() {
    this.getUserContacts();
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
</style>
