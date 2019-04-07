@extends('layouts.app')

@section('content')

<div id="chat-panel-wrapper" class="row">
  <div class="col-md-2 chat-sidebar">
    Chat Sidebar
  </div>

  <div class="col-md-10 p-0 chat-content">

    <div class="chat-messages h-75">
      <chat-messages
        v-on:messagesent="addMessage"
        :messages="messages"
        :user="{{ Auth::user() }}"
      ></chat-messages>
    </div>

    <div class="chat-message-form h-25 p-3">
      <chat-form
          v-on:messagesent="addMessage"
          :user="{{ Auth::user() }}"
          :canned-messages="{{ $cannedMessages }}"
      ></chat-form>
    </div>

  </div>
</div>

@endsection
