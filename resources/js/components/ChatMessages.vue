<template>
    <ul class="chat">
        <li
          class="p-3"
          :class="{ 'text-right' : message.user.id !== user.id}"
          v-for="(message,index) in messages">
            <div class="chat-body">
                <div class="header">
                    <strong class="primary-font">
                        {{ message.user.name }} <small>{{ message.created_at | moment("ddd, MMM Do h:mma") }}</small>
                    </strong>
                </div>
                <p>
                    {{ message.message }}
                </p>

                <div v-if="message.replied">
                  <h1>this has been replied</h1>
                </div>
                <div v-else>
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
</template>

<script>

  export default {
    props: ['messages', 'user'],

    data() {
      return {

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

    methods: {
        sendMessage(messageIndex, response, messageId) {
            this.messages[messageIndex].replied = 1;
            this.$emit('messagesent', {
                user: this.user,
                message: response,
                parent_message_id: messageId
            });
        }
    },

  };
</script>
