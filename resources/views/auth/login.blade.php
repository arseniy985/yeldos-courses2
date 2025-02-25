@extends('layouts.app')

@section('content')
    <div class="login-container">
        <div class="login-box">
            <h2>Log In</h2>
            <div class="social-login">
                <a href="{{ url('auth/github/redirect') }}"><img src="{{ asset('images/github-icon.png') }}" alt="GitHub"></a>
                <a href="{{ url('auth/google/redirect') }}"><img src="{{ asset('images/google-icon.png') }}" alt="Google"></a>
            </div>
            <form method="POST" action="{{ route('login') }}">
{{--                @csrf--}}
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary">Log In</button>
            </form>
            <a href="{{ route('password.request') }}">Forgot your password?</a>
            <a href="{{ route('register') }}">Don't have an account?</a>
        </div>
    </div>

    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

        .login-box {
            background: #2b3e50;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
            color: white;
        }

        .social-login {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 15px;
        }

        .social-login img {
            width: 40px;
            height: 40px;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        a {
            display: block;
            margin-top: 10px;
            color: #fff;
        }
    </style>
@endsection
