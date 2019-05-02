<?php

namespace App\Http\Controllers;

use App\User;
use App\Message;
use App\CannedMessage;
use App\ContactNotification;
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
    $contactNotifications = ContactNotification::select('contact_id')->where('customer_id', '=', Auth::user()->id)->get();
    return view('chat', compact(['cannedMessages', 'contactNotifications']));
  }

  /**
  * Fetch all messages
  *
  * @return Message
  */
  public function fetchMessages($contactId = null)
  {
    $user = Auth::user();

    $messages = Message::whereRaw("
      (messages.recipient_id = $contactId AND messages.user_id = $user->id OR
      messages.recipient_id = $user->id AND messages.user_id = $contactId)
    ")->with('user')->limit(50)->get();

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
      'canned_message_id' => $request->input('canned_message_id'),
      'recipient_id' => $request->input('recipient_id')
    ]);

    $recipient = User::where(['id' => $request->input('recipient_id')])->first();

    if($recipient->status === 'offline') {
      $this->addContactNotification($request->input('recipient_id'));
    }

    broadcast(new MessageSent($user, $message))->toOthers();

    if(is_integer($request->input('parent_message_id'))) {
      $parentMessage = Message::where('id', '=', $request->input('parent_message_id'))->first();
      $parentMessage->replied = true;
      $parentMessage->save();
    }

    return ['status' => 'Message Sent!'];
  }

  /**
  * Get responses for a canned message
  *
  * @param  int $id
  * @return json
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

  /**
  * Get the active user accessing the site
  * NOTE There is a difference between this and the message sender (user)
  *
  * @return json
  */
  public function getCurrentAppUser()
  {
    return ['user_id' => Auth::user()->id, 'api_token' => Auth::user()->api_token];
  }

  /**
  * Get responses for a canned message
  *
  * @param  int $id
  * @return json
  */
  public function getUserContacts()
  {
    $user = Auth::user();

    $userContacts = User::where('id', '!=', $user->id);

    // Admins can see all contacts
    if($user->position !== 'Admin') {
      $userContacts = $userContacts->where(['portfolio' => $user->portfolio])->orWhere(['position' => 'Admin']);
    }

    $userContacts = $userContacts->orderBy('name', 'ASC')->get();

    return $userContacts;
  }

  /**
  * Get all users online status
  *
  * @param  int $id
  * @return json
  */
  public function getUserContactsOnlineStatus() {

    return User::select(['id','status'])->get();

  }

  /**
  * Add contact to the contact_notifications table
  *
  * @param  int $id
  * @return json
  */
  public function addContactNotification($from)
  {
    $to = Auth::user()->id;

    $contactNotification = ContactNotification::create([
      'customer_id' => $from,
      'contact_id' => $to
    ]);

    return $contactNotification;
  }

  /**
  * Remove contact to the contact_notifications table
  *
  * @param  int $id
  * @return json
  */
  public function removeContactNotification($contactId)
  {
    $user = Auth::user();

    $contactNotification = ContactNotification::where([
      'customer_id' => $user->id,
      'contact_id' => $contactId
    ])->delete();

    return $contactNotification;
  }

}
