<template>
    <ul class="chat">
        <li class="left clearfix p-3" v-for="message in messages">
            <div class="chat-body clearfix">
                <div class="header">
                    <strong class="primary-font">
                        {{ message.user.name }}
                    </strong>
                </div>
                <p>
                    {{ message.message }}
                </p>
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
      scrollToBottomOfMessages() {
        $('.chat-messages').scrollTop($('.chat-messages')[0].scrollHeight);
      }
    },

    created() {
    }

  };
</script>
