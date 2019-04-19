<template>

  <div>

      <button class="btn btn-sm btn-warning rounded-0 toggle-contacts" @click.prevent="toggleContacts">
        <i
          class="fas fa-arrow-left"
          :class="contactsHidden ? 'fa-arrow-right' : 'fa-arrow-left'"></i>
      </button>

      <button class="btn btn-sm btn-primary rounded-0" @click.prevent="toggleNavbar">
        <i
          class="fas"
          :class="navbarHidden ? 'fa-arrow-down' : 'fa-arrow-up'"></i>
      </button>

      <div
        v-if="contactNotifications.length > 0"
        @click="toggleContacts"
        style="cursor:pointer;"
        class="p-1 ml-1 alert-danger h1 float-right contact-blink">
        <i class="fas fa-comments"></i>
      </div>

      <div
        v-if="currentContactName !== null"
        style="background-color: #3F0E40;"
        class="p-1 text-light float-right">
        <i class="fas fa-comment-dots"></i> Chatting with {{ currentContactName }}
      </div>

      <div v-if="currentContact !== null">
          <ul class="chat">
              <li
                class="p-3"
                :class="{ 'text-right' : message.user.id !== user.id}"
                v-for="(message,index) in messages">
                  <div class="chat-body">
                      <div class="header">
                          <strong class="primary-font">
                              {{ message.user.name }} <small>{{ message.created_at | moment("ddd, MMM Do h:mma") }}</small>
                              <span v-if="message.replied">
                                <i class="fas fa-comment-dots text-info" style="font-size: 1.18rem;"></i>
                              </span>
                          </strong>
                      </div>
                      <p>
                          {{ message.message }}
                      </p>

                      <div v-if="!message.replied">
                          <div v-if="message.user.id !== user.id && message.canned_message_responses !== ''">
                              <div
                                  class="btn btn-sm btn-primary ml-1"
                                  @click="sendMessage(index, response, message.id)"
                                  v-for="response in JSON.parse(message.canned_message_responses)">
                                  {{ response }}
                              </div>
                          </div>
                      </div>

                  </div>
              </li>
          </ul>
      </div>
      <div v-else class="h3 text-center mt-5" style="font-size: 2.5em; color: rgba(0,0,0,.3);">
        Select a contact to begin chatting
      </div>
  </div>

</template>

<script>

  import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';

  export default {
    props: ['messages', 'user'],

    data() {
      return {
        navbarHidden: false,
        contactsHidden: false,
      }
    },

    watch: {
      'messages': {
        handler: function(messages) {
          setTimeout(function() {
              $('.chat-messages').scrollTop($('.chat-messages')[0].scrollHeight);
          }, 0);
        }
      }
    },

    computed: {
      ...mapState('chatStore',
      [
        'currentContact',
        'currentContactName',
        'contactNotifications'
      ]),
    },

    methods: {
        sendMessage(messageIndex, response, messageId) {
            this.messages[messageIndex].replied = 1;
            this.$emit('messagesent', {
                user: this.user,
                message: response,
                parent_message_id: messageId,
                recipient_id: this.currentContact
            });
        },

        toggleNavbar() {
          this.navbarHidden = !this.navbarHidden;
          $(".navbar-laravel").toggle();
        },

        toggleContacts() {
          this.contactsHidden = !this.contactsHidden;
          $(".chat-sidebar").toggle();
          if(this.contactsHidden) {
            $(".chat-content").removeClass("col-8 col-md-10");
            $(".chat-content").addClass("col-12");
          } else {
            $(".chat-content").removeClass("col-12");
            $(".chat-content").addClass("col-8 col-md-10");
          }
        }
    },

  };
</script>

<style lang="css">

</style>
