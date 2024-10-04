<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-8">
            <div class="row" style="box-shadow: 0 0 10px #80808047; margin: 20px; border-radius:20px;">
                <div class="col" style="background-image: linear-gradient(0deg, #004d8f, #00b7ff); padding:20px 0; border-radius:20px 0 0 20px; display: inline-grid; align-content: center;">
                    <form method="POST" action="{{ route('register') }}" id="rg-form">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-8 offset-md-2">
                                <h4 style="color: white; font-weight:600; text-align:center;">WELCOME</h4>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-8 offset-md-2">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Name" style="border-radius:20px; text-align:center;">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-8 offset-md-2">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email" style="border-radius:20px; text-align:center;">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-8 offset-md-2">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Password" autocomplete="new-password" style="border-radius:20px; text-align:center;">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-8 offset-md-2">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm Password" autocomplete="new-password" style="border-radius:20px; text-align:center;">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-2">
                                <button type="submit" class="btn btn-success" style="border-radius:20px; width:100%; background: #11cc97">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col" style="padding:40px 0 20px 0; display: inline-grid; align-content: center;">
                    <img src="/img/login_des.png" style="width:100%; height:auto;">
                </div>
            </div>
        </div>
    </div>
</div>
