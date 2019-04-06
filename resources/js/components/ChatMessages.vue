<template>
    <ul class="chat">
        <li
          class="p-3"
          :class="{ 'text-right' : message.user.id !== user.id}"
          v-for="message in messages">
            <div class="chat-body">
                <div class="header">
                    <strong class="primary-font">
                        {{ message.user.name }} <small>{{ message.created_at | moment("ddd, MMM Do h:ma") }}</small>
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
      
    },

  };
</script>
