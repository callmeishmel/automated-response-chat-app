<?php

namespace App\Http\Controllers;

use App\Message;
use App\CannedMessage;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
  * Show chats
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {

    $cannedMessages = CannedMessage::all();
    return view('chat', compact('cannedMessages'));
  }

  /**
  * Fetch all messages
  *
  * @return Message
  */
  public function fetchMessages()
  {
    $messages = Message::with('user')->get();

    foreach($messages as $message) {
      if(!is_null($message->canned_message_id)) {
        $cannedMessages = CannedMessage::select('possible_responses')->
        where('id', '=', $message->canned_message_id)->first();
        $message['canned_message_responses'] = $cannedMessages->possible_responses;
      } else {
        $message['canned_message_responses'] = null;
      }
    }

    return $messages;
  }

  /**
  * Persist message to database
  *
  * @param  Request $request
  * @return Response
  */
  public function sendMessage(Request $request)
  {

    $user = Auth::user();

    $message = $user->messages()->create([
      'message' => $request->input('message'),
      'parent_message_id' => $request->input('parent_message_id'),
      'canned_message_id' => $request->input('canned_message_id')
    ]);

    broadcast(new MessageSent($user, $message))->toOthers();

    if(is_integer($request->input('parent_message_id'))) {
      $parentMessage = Message::where('id', '=', $request->input('parent_message_id'))->first();
      $parentMessage->replied = true;
      $parentMessage->save();
    }

    return ['status' => 'Message Sent!'];
  }

  /**
  * Persist message to database
  *
  * @param  Request $request
  * @return Response
  */
  public function getCannedMessageResponses($id)
  {
    $responses = null;
    $cannedMessage = CannedMessage::select('possible_responses')->where('id', '=', $id)->first();

    if($cannedMessage) {
      $responses = json_encode($cannedMessage->possible_responses);
    }

    return $responses;
  }
}
