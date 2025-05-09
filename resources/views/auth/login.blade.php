@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide">

<style>
.navbar {
    display: none;
}

body {
    background-image: url('{{ url('/img/log-body-bg.jpg') }}');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center;
    background-size: cover;
}

.above-btn * {
    font-size: 12px !important;
}

.above-btn .form-check {
    margin-bottom: -12px;
    margin-top: 2px;
}

.login-logo-cont {
    min-height: 450px; 
    padding:60px 0 20px 0; 
    display: inline-grid; 
    align-content: center; 
    background-color: #000000a3;
}

.login-logo {
    width: 60%; 
    height: auto; 
    margin: 40px auto 70px auto; 
    margin-bottom: 50px; 
    border-radius: 15px;
}

@media (max-width: 678px) {
.login-logo-cont {
    min-height: unset;
}

.login-logo {
    margin: 10px auto 40px auto; 
    margin-bottom: 40px;
}
}
</style>

<div class="container my-auto">
    <div class="row justify-content-center" style="min-height: calc(100vh - 50px);">
        <div class="col-sm-8 my-auto">
            <div class="d-flex flex-column-reverse flex-md-row" style="box-shadow: 0 0 10px #1b1b1b47; margin: 20px; border-radius:2px;">
                <div class="col p-4" style="backdrop-filter: blur(10px); background-color: hsla(0, 0%, 10%, 0.1); padding:20px 0; border-radius:2px 0 0 2px; display: inline-grid; align-content: center;">
                    <form method="POST" action="{{ route('login') }}" id="lg-form">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-8 offset-md-2">
                                <h4 style="color: white; font-weight:600; text-align:center;">WELCOME</h4>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-8 offset-md-2">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email" style="border-radius:20px; text-align:center;">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-8 offset-md-2">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password" style="border-radius:20px; text-align:center;">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex col-12 col-lg-8 m-auto ms-auto justify-content-between px-2 mb-3 above-btn">
                            <div class="col-md-6 text-nowrap pe-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label text-white" for="remember" style="font-size: 14px;">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 text-end ps-1">
                                @if (Route::has('password.request'))
                                    <a class="btn form-check-label text-nowrap text-white" href="{{ route('password.request') }}" style="padding:0; font-size: 14px;">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-2">
                                <button type="submit" class="btn btn-success" style="border-radius:20px; width:100%; background: #538840">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    <small class="text-center mt-4"><a class="text-white" href="{{ url('/') }}">
                        <i class="fa-solid fa-arrow-left me-1"></i> Go to {{ config('app.name', 'Laravel') }}
                    </a></small>
                </div>
                <div class="col login-logo-cont">
                    <h1 class="text-center text-white" style="font-family: 'Audiowide', sans-serif;">{{ config('app.name', 'Laravel') }}</h1>
                    <a href="{{ url('/') }}" class="text-center">    
                        <img src="{{ asset('img') }}/{{ config('app.logo', 'Laravel') }}" class="p-2 login-logo">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
