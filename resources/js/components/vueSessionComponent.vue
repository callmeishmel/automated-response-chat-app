<template>

  <div>

    <transition name="modal">
      <div v-if="sessionExpiring" class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container p-1">

            <div class="modal-body m-0">
              <slot name="body">
                <div class="row mb-3">
                  <div class="col-12 text-center my-4">
                    <h4>Session will expire in<br/>{{ secondsUntilAutoLogout }} seconds</h4>
                  </div>
                </div>

                <div class="row">
                  <div class="col-6 px-1">
                    <button class="btn btn-block btn-primary" @click="continueSession()">
                      Continue
                    </button>
                  </div>
                  <div class="col-6 px-1">
                    <button class="btn btn-block btn-secondary" @click="sessionLogout()">
                      Log Out
                    </button>
                  </div>
                </div>

              </slot>
            </div>

          </div>
        </div>
      </div>
    </transition>

  </div>

</template>

<script>

export default {

  props: ['logoutRoute', 'sessionTimeInSeconds'],

  data() {
    return {
      currentSessionTime: this.sessionTimeInSeconds,
      secondsUntilAutoLogout: 10,
      sessionExpiring: false
    }
  },

  methods: {

    continueSession() {
      this.sessionExpiring = false;
      this.currentSessionTime = this.sessionTimeInSeconds;
      this.secondsUntilAutoLogout = 10;
    },

    sessionLogout() {
      location.replace(this.logoutRoute);
    },

    timeoutCountdown() {
      setInterval(() => {

        if(this.sessionExpiring) {
          this.secondsUntilAutoLogout--;
        }

        if(this.secondsUntilAutoLogout <= 0) {
          location.replace(this.logoutRoute);
        }

      }, 1000);
    },

    sessionCountdown() {
      setInterval(() => {
        if(this.currentSessionTime < 1) {
          this.sessionExpiring = true;
        } else {
          this.currentSessionTime--;
        }
      }, 1000);
    }

  },

  mounted() {

    if(!this.sessionExpiring) {
      this.sessionCountdown();
    }

    this.timeoutCountdown();

  }

}
</script>

<style lang="css">

.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 300px;
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}

</style>
