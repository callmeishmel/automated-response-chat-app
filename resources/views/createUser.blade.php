@extends('layouts.app')

@section('content')

<div class="row">

  <div class="col-md-4 offset-4 p-5">

    @if(session()->has('message-success'))
      <div class="alert alert-success text-center">
        {{ session()->get('message-success') }}
      </div>
    @endif

    @if(session()->has('message-failure'))
      <div class="alert alert-danger text-center">
        {{ session()->get('message-failure') }}
      </div>
    @endif

    <h3>Create User</h3>

    <hr>

    <form method="post" class="admin-form" autocomplete="off">
      <div class="form-group">
        @csrf
        <label for="name">
          <div class="float-left">Name:</div>
          @if($errors->has('name'))
              <div class="float-right invalid-submission">{{ $errors->first('name') }}</div>
          @endif
        </label>
        <input type="text" class="form-control" name="name"/>
      </div>
      <div class="form-group">
        <label for="email">
          <div class="float-left">Email:</div>
          @if($errors->has('email'))
              <div class="float-right invalid-submission">{{ $errors->first('email') }}</div>
          @endif
        </label>
        <input type="text" class="form-control" name="email"/>
      </div>
      <div class="form-group">
        <label for="password">
          <div class="float-left">Password:</div>
          @if($errors->has('password'))
              <div class="float-right invalid-submission">{{ $errors->first('password') }}</div>
          @endif
        </label>
        <input type="password" class="form-control" name="password" autocomplete="new-password"/>
      </div>
      <div class="form-group">
        <label for="password_confirmation">
          <div class="float-left">Verify Password:</div>
          @if($errors->has('password_confirmation'))
              <div class="float-right invalid-submission">{{ $errors->first('password_confirmation') }}</div>
          @endif
        </label>
        <input type="password" class="form-control" name="password_confirmation" autocomplete="new-password"/>
      </div>
      <div class="form-group">
        <label for="portfolio">
          <div class="float-left">Portfolio:</div>
          @if($errors->has('portfolio'))
              <div class="float-right invalid-submission">{{ $errors->first('portfolio') }}</div>
          @endif
        </label>
        <input type="text" class="form-control" name="portfolio"/>
      </div>
      <div class="form-group">
        <label for="team">
          <div class="float-left">Team:</div>
          @if($errors->has('team'))
              <div class="float-right invalid-submission">{{ $errors->first('team') }}</div>
          @endif
        </label>
        <input type="text" class="form-control" name="team"/>
      </div>
      <div class="form-group">
        <label for="position">
          <div class="float-left">Position:</div>
          @if($errors->has('position'))
              <div class="float-right invalid-submission">{{ $errors->first('position') }}</div>
          @endif
        </label>
        <select class="form-control" name="position">
          <option value="Admin">Admin</option>
          <option value="RM">RM</option>
          <option value="TL">TL</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Submit</button>
    </form>

  </div>
</div>

@endsection
