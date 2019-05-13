<template>

  <div style="position: relative;">

      <div class="position-fixed" style="right: 15px; z-index:9999; background-color: #fff;">

        <div
          v-if="contactNotifications.length > 0"
          v-for="contactId in contactNotifications"
          style="cursor:pointer;"
          class="p-1 ml-1 alert-danger float-right contact-blink">
          <div
            v-for="contact in userContacts"
            @click="setNewContact({id:contact.id, name:contact.name})"
            v-if="contact.id === contactId">
            <div v-if="contact.status === 'online'">
              {{ contact.name }} <i class="fas fa-user-circle"></i>
            </div>
            <div v-else>
              {{ contact.name }} <i class="fas fa-minus-circle"></i>
            </div>
          </div>
        </div>

      </div>

      <div class="position-fixed py-1 px-2 mt-1 rounded" style="background-color: #fff; z-index: 999;">
        <button class="btn btn-sm btn-warning rounded-0 toggle-contacts" @click.prevent="toggleContacts">
          <i
            class="fas fa-arrow-left"
            :class="contactsHidden ? 'fa-arrow-right' : 'fa-arrow-left'"></i>
        </button>

        <button class="btn btn-sm btn-primary mr-1 rounded-0" @click.prevent="toggleNavbar">
          <i
            class="fas"
            :class="navbarHidden ? 'fa-arrow-down' : 'fa-arrow-up'"></i>
        </button>

        <div
          v-if="currentContactName !== null"
          style="background-color: #3F0E40;"
          class="p-1 text-light float-right">
          <i class="fas fa-comment-dots"></i> Chatting with {{ currentContactName }}
        </div>
      </div>

      <div class="pt-5" v-if="currentContact !== null">

        <div class="col-md-12" style="overflow:hidden;" v-for="(message,index) in messages">

          <div
            class="round-bubble my-2 py-2 px-4"
            :class="message.user.id !== user.id ? 'from-bubble float-left' : 'float-right text-right to-bubble text-white'">
              <div class="chat-body">
                  <div class="header">
                      <strong class="primary-font">
                          {{ message.user.name }} <small>{{ message.created_at | moment("ddd, MMM Do h:mma") }}</small>
                          <span v-if="message.replied">
                            <i class="fas fa-comment-dots text-info" style="font-size: 1.18rem;"></i>
                          </span>
                      </strong>
                  </div>
                  <p style="font-size: 1.1rem;">
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
          </div>
        </div>

      </div>
      <div v-else class="h3 text-center mt-5" style="display: inline-block; width: 100%; font-size: 2.5em; color: rgba(0,0,0,.3);">
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
      },

      // Not proud of this one but this is the one quick way to clear the current
      // contact's notifications that cannot be caught by clicking on the contact
      // name on the sidebar
      'userContacts': {
        handler: function(contacts) {
          if(this.user.current_contact !== null && this.currentContact === null) {

            for(let index in this.userContacts) {
              if(this.userContacts[index].id === this.user.current_contact) {
                this.setNewContact({id:this.user.current_contact, name:this.userContacts[index].name});
              }
            }
          }
        }
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

        sendMessage(messageIndex, response, messageId) {
            this.messages[messageIndex].replied = 1;
            this.$emit('messagesent', {
                user: this.user,
                message: response,
                parent_message_id: messageId,
                recipient_id: this.currentContact
            });
        },

        setNewContact(contact) {
          this.setNewContactInStore(contact);
          if(this.contactNotifications.includes(contact.id)) {
            this.removeFromContactNotifications(contact.id);
          }

          axios.get('remove-contact-notification/' + contact.id).then(() => {});
        },

        toggleNavbar() {
          this.navbarHidden = !this.navbarHidden;
          $(".navbar-laravel").toggle();
          if(!this.navbarHidden) {
            $(".chat-sidebar").css({maxHeight: 'calc(100vh - 55px)'});
          } else {
            $(".chat-sidebar").css({maxHeight: '100vh'});
          }
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

.from-bubble {
  background-color: #e5e5ea;
}

.to-bubble {
  background-color: #0b84ff;
}

.round-bubble {
  border-radius: 1rem;
}

</style>
