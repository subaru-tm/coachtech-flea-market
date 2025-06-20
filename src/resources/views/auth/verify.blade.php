@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class.="card-header">{{ __('登録していただいたメールアドレスに認証メールを送付しました。') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('認証メールを再送しました。') }}
                        </div>
                    @endif

                    {{ __('メール認証を完了してください。') }}

                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('認証メールを再送する') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection