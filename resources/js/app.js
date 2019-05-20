
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// Moment.js
Vue.use(require('vue-moment'));

/* Idle Vue */

import IdleVue from 'idle-vue';

const eventsHub = new Vue();

const idleViewOptions = {
  eventEmitter: eventsHub,
  idleTime: 120000
}
Vue.use(IdleVue, idleViewOptions);

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
Vue.component('vueSessionComponent', require('./components/vueSessionComponent.vue').default);

/**
 * Import transition components
 */
import {FadeTransition} from 'vue2-transitions';
import {ZoomCenterTransition} from 'vue2-transitions';
import {ZoomXTransition} from 'vue2-transitions';
import {ZoomYTransition} from 'vue2-transitions';
import {CollapseTransition} from 'vue2-transitions';
import {ScaleTransition} from 'vue2-transitions';
import {SlideXLeftTransition} from 'vue2-transitions';
import {SlideXRightTransition} from 'vue2-transitions';
import {SlideYUpTransition} from 'vue2-transitions';
import {SlideYDownTransition} from 'vue2-transitions';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components: {
      FadeTransition,
      ZoomCenterTransition,
      ZoomXTransition,
      ZoomYTransition,
      CollapseTransition,
      ScaleTransition,
      SlideXLeftTransition,
      SlideXRightTransition,
      SlideYUpTransition,
      SlideYDownTransition,
    },
    store: store,
    data: {
        // currentAppUser is who is accessing the site not sending the messages
        userAuth: false,
        sessionLifetime: null,
        currentAppUser: null,
        contactId: null,
        messages: [],
    },

    beforeMount() {
      this.userAuth = this.$el.attributes['user-auth'].value;

      var vm = this;

      if(vm.userAuth) {

        this.fetchMessages(vm.contactId);

        axios.get('/current-app-user').then(response => {
          Echo.private('chat.user.' + response.data.user_id)
          .listen('MessageSent', (e) => {

              // Play sound and add to notifications upon receiving message if
              // currentAppUser is not actively selecting sender
              vm.playSound('/sounds/appointed.mp3');
              store.commit('chatStore/addToContactNotifications', e.message.user_id);

              if(e.user.id !== vm.currentContact) {
                Notification.requestPermission( permission => {
                  let notification = new Notification('New message from ' + e.user.name + ' (' + e.user.portfolio + ')', {
                    body: e.message.message, // content for the alert
                    icon: "/img/favicon.png" // optional image url
                  });

                  // link to page on clicking the notification
                  notification.onclick = () => {
                    window.open(window.location.href);
                  };
                });
              }

              // NOTE chat channel is in app/Events/MessageSent.php
              if(vm.currentContact === e.message.user_id) {
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

        });

      }
    },

    mounted() {
      if(this.userAuth) {
        this.listen();
      }
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

        listen() {
            Echo.join('chat')
                .joining((user) => {
                    axios.put('/api/user/'+ user.id +'/online?api_token=' + user.api_token, {});
                })
                .leaving((user) => {
                    axios.put('/api/user/'+ user.id +'/offline?api_token=' + user.api_token, {});
                })
                // Listen functions are only here as a look out for Pusher's
                // presence event messages being turned, these should never
                // trigger. This saves on Pusher's message bandwidth.
                // READ: https://support.pusher.com/hc/en-us/articles/360019620253-How-can-I-implement-large-presence-channels-on-Channels-
                .listen('UserOnline', (e) => {
                    console.log('UserOnline', e);
                })
                .listen('UserOffline', (e) => {
                    console.log('UserOffline', e);
                });
        },

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
              // console.log(response.data);
            });

            axios.get('add-contact-notification/' + message.recipient_id).then(response => {
              // console.log('notification', response.data);
            });
        },

        playSound (sound) {
          if(sound) {
            var audio = new Audio(sound);
            audio.play();
          }
        },

    }
});
