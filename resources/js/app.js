
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// Moment.js
Vue.use(require('vue-moment'));

// Vuex stores
import Vuex from 'vuex';
import store from './vuex/store.js';
Vue.use(Vuex);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('chat-contacts', require('./components/ChatContacts.vue').default);
Vue.component('chat-messages', require('./components/ChatMessages.vue').default);
Vue.component('chat-form', require('./components/ChatForm.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    store: store,
    data: {
        // currentAppUser is who is accessing the site not sending the messages
        currentAppUser: null,
        contactId: null,
        messages: []
    },

    created() {
        var vm = this;
        this.getCurrentAppUser();
        this.fetchMessages(vm.contactId);

        Echo.private('chat')
        .listen('MessageSent', (e) => {

            // Light up the user's name on the recipients client contact list
            if(vm.currentAppUser === e.message.recipient_id && !vm.contactNotifications.includes(e.message.user_id)) {
              store.commit('chatStore/addToContactNotifications', e.message.user_id);

              axios.get('add-contact-notification/' + e.message.user_id).then(response => {});
            }

            // The following condition is messy and heavy but necessary in order
            // To keep conversations between two users without running the temp
            // message update to all customers listening to the 'chat' channel
            // NOTE chat channel is in app/Events/MessageSent.php
            if(vm.currentAppUser === e.message.recipient_id && vm.currentContact === e.message.user_id) {
              axios.get('/canned-message-responses/' + e.message.canned_message_id).
              then(response => {
                this.messages.push({
                  user: e.user,
                  message: e.message.message,
                  recipient_id: e.message.recipient_id,
                  created_at: e.message.created_at,
                  canned_message_id: e.message.canned_message_id,
                  canned_message_responses: response.data,
                });
              });
            }
        });
    },

    computed: {
      currentContact() {
        return store.state.chatStore.currentContact;
      },

      contactNotifications() {
        return store.state.chatStore.contactNotifications;
      }
    },

    watch: {
      currentContact (newValue, oldValue) {
        this.fetchMessages(newValue);
      }
    },

    methods: {
        fetchMessages(contactId) {
            axios.get('/messages/' + contactId).then(response => {
                this.messages = response.data;
            });
        },

        getCurrentAppUser() {
            axios.get('/current-app-user').then(response => {
                this.currentAppUser = response.data;
            });
        },

        addMessage(message) {
            message.created_at = Vue.moment.now();
            this.messages.push(message);

            axios.post('/messages', message).then(response => {
              console.log(response.data);
            });
        },

    }
});
