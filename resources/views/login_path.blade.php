@extends('login_page')

@section("contents")
<form action="{{ route('loginPostAdmin') }}" id="login_form" name="login", method="POST">
    <h1>Login</h1>
    @csrf
    <div class="input_box">
      <input type="text" placeholder="email" id="email" name="email" required>
      <i class='bx bxs-user'></i>
    </div>
    <div class="input_box">
      <input type="password" placeholder="Password" id="password" name="password" required>
      <i class='bx bxs-lock-alt'></i>
    </div>
    <div class="remember_forget">
      <label>
          <input type="checkbox" name="remember_forget">
          Remember me.
      </label>
      <a href="#">Forgot Password?</a>
    </div>
    <button type="submit" class="btn" onClick="window.location.href='{{ route("layout",['userEmail' => 'ok']) }}">Login</button>
</form>
<div class="no_account">
    <label>Don't have an account yet?</label>
    <a href="{{url('/register_section')}}">Register Account</a>
</div>
@endsection
