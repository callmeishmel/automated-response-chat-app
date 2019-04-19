<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class AdminController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
  * Show admin page
  *
  * @return \Illuminate\Http\Response
  */
  public function createUserPage()
  {

    if (Auth::user()->position === 'RM') {
      return redirect('/');
    }
    return view('createUser');

  }

  public function createUserPost(Request $request)
  {

    // Validate form data
    $validatedData = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|same:password_confirmation',
        'portfolio' => 'string|nullable',
        'team' => 'string|nullable',
        'position' => 'required|string',
    ]);

    try {
      // do your database transaction here
      $post = $request->all();

      $newUser = User::create([
        'name' => $post['name'],
        'email' => $post['email'],
        'password' => Hash::make($post['password']),
        'portfolio' => $post['portfolio'],
        'team' => $post['team'],
        'position' => $post['position']
      ]);

      if($newUser) {
        return redirect()->back()->with('message-success', 'User ' . $post['name'] . ' creation successful');
      }
    } catch (\Illuminate\Database\QueryException $e) {
        // something went wrong with the transaction, rollback
        return redirect()->back()->with('message-failure', 'User ' . $post['name'] . ' creation failed: ' . $e->getMessage());
    } catch (\Exception $e) {
        // something went wrong elsewhere, handle gracefully
        return redirect()->back()->with('message-failure', 'User ' . $post['name'] . ' creation failed: ' . $e->getMessage());
    }

  }
}
