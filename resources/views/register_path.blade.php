@extends('login_page')

@section("contents")
    <div id="loginForm">
        <form action="{{ route('registerPostAdmin') }}" method="POST" id="login_form" name="login">
            @csrf
            <h1>Register</h1>
            <div class="input_box">
                <input type="text" placeholder="Username" id="username" name="username" required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="input_box">
                <input type="text" placeholder="Email" id="email" name="email" required>
                <i class='bx bxs-envelope'></i>
            </div>
            <div class="input_box">
                <input type="password" placeholder="Password" id="password" name="password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>

                <div class="remember_forget">
                    <label>
                        <input type="checkbox" name="terms"> I agree to the Terms and Privacy Policy.
                    </label>
                </div>


            <button type="submit" class="btn">Create Account</button>
        </form>
        <div class="no_account">
            <label>Already have account?</label>
            <a href="{{url('/login_section')}}">Login Account</a>
        </div>
    </div>

@endsection
