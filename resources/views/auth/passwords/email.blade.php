@extends('layouts.auth')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="text-center mb-4">
                <h2 class="text-dark mb-2">{{ __('Reset Password') }}</h2>
                <p class="text-muted">Masukkan email Anda untuk reset password</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Masukkan email Anda">
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <div class="d-grid gap-2 mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane me-2"></i>
                        {{ __('Send Password Reset Link') }}
                    </button>
                </div>

                <div class="text-center">
                    <a class="btn btn-link" href="{{ route('login') }}">
                        <i class="fas fa-arrow-left me-2"></i>
                        {{ __('Back to Login') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
