@extends('layouts.app')

@section('content')
    <div class="container" style="min-height: 50vh;margin:15vh auto;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center text-white bg-dark">{{ __('Verify Your Email Address') }}</div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                        @endif

                        {{ __('Before proceeding, please check your email for a verification link.') }}
                        {{ __('If you did not receive the email') }},
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit"
                                    class="btn btn-outline-light btn-link p-0 m-0 align-baseline rounded-pill text-white my-sm-0"
                                    style="background-color:#880000;box-shadow: rgba(116, 129, 141, 0.1) 0 4px 8px 0">{{ __('click here to request another') }}</button>
                            .
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
