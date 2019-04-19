<template>

    <div v-if="currentContact !== null">

      <div class="input-group" v-if="isCannedMessage">

          <select
            name="message"
            class="form-control"
            v-model="selectedCannedMessageOption"
            @keyup.enter="sendMessage">
            <option v-for="(message, index) in cannedMessages" :value="index">{{ message.message }}</option>
          </select>

          <span class="input-group-btn">
              <button class="ml-1 btn btn-primary" id="btn-chat" @click="sendMessage">
                  Send
              </button>

              <button v-if="user.position !== 'RM'" class="ml-1 btn btn-success" id="btn-chat" @click="changeMessageType()">
                  Regular Message
              </button>
          </span>
      </div>
      <div class="input-group" v-else>
          <input
            id="btn-input"
            type="text"
            name="message"
            class="form-control input-sm"
            placeholder="Type your message here..."
            v-model="newMessage"
            @keyup.enter="sendMessage">

          <span class="input-group-btn">
              <button class="ml-1 btn btn-primary" id="btn-chat" @click="sendMessage">
                  Send
              </button>

              <button class="ml-1 btn btn-warning" id="btn-chat" @click="isCannedMessage = !isCannedMessage">
                  Canned Response
              </button>
          </span>
      </div>

      <div
        class="col-md-12 p-3 text-light"
        v-if="selectedCannedMessage !== null">
          <label class="h5">Possible Responses</label>
          <span
            class="p-2 ml-2 border border-light rounded"
            v-for="response in JSON.parse(selectedCannedMessage.possible_responses)">
            {{ response }}
          </span>
      </div>

    </div>

</template>

<script>

    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';

    export default {
        props: ['user', 'cannedMessages'],

        data() {
            return {
                newMessage: '',
                isCannedMessage: true,
                selectedCannedMessage: null,
                selectedCannedMessageOption: null
            }
        },

        computed: {
          ...mapState('chatStore',
          [
            'currentContact',
            'currentContactName'
          ]),
        },

        watch: {
          'selectedCannedMessageOption': {
            handler: function(selectOption) {
              if(this.selectedCannedMessageOption !== null) {
                this.newMessage = this.cannedMessages[selectOption].message;
                this.selectedCannedMessage = this.cannedMessages[selectOption];
              } else {
                this.newMessage = '';
                this.selectedCannedMessage = null;
              }
            }
          }
        },

        methods: {
            sendMessage() {
                var cannedMessageId = this.selectedCannedMessage !== null ? this.selectedCannedMessage.id : null;
                var cannedMessageResponses = this.selectedCannedMessage !== null ? this.selectedCannedMessage.possible_responses : null;

                this.$emit('messagesent', {
                    user: this.user,
                    message: this.newMessage,
                    recipient_id: this.currentContact,
                    canned_message_id: cannedMessageId,
                    canned_message_responses: cannedMessageResponses
                });

                this.selectedCannedMessageOption = null;
                this.newMessage = '';
            },

            changeMessageType() {
              this.newMessage = '';
              this.selectedCannedMessageOption = null;
              this.isCannedMessage = !this.isCannedMessage;
            },

        }
    }
</script>

<style lang="css">

</style>
