@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">アカウント編集</div>

                <form method="POST" action="/user/account">
                    <div class="card-body">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">

                        <div class="form-group row">
                            <label for="user__name" class="col-md-4 col-form-label text-md-right">氏名</label>

                            <div class="col-md-6">
                                <input id="user__name" type="text" class="form-control{{ $errors->has('user.name') ? ' is-invalid' : '' }}" name="user[name]" value="{{ old('user.name', $me->name) }}" required autofocus>

                                @if ($errors->has('user.name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('user.name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="user__email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>

                            <div class="col-md-6">
                                <input id="user__email" type="email" class="form-control{{ $errors->has('user.email') ? ' is-invalid' : '' }}" name="user[email]" value="{{ old('user.email', $me->email) }}" required>

                                @if ($errors->has('user.email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('user.email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="user__password" class="col-md-4 col-form-label text-md-right">パスワード</label>

                            <div class="col-md-6">
                                <input id="user__password" type="password" class="form-control{{ $errors->has('user.password') ? ' is-invalid' : '' }}" name="user[password]">

                                @if ($errors->has('user.password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('user.password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="user__password_confirmation" class="col-md-4 col-form-label text-md-right">パスワード（確認）</label>

                            <div class="col-md-6">
                                <input id="user__password_confirmation" type="password" class="form-control{{ $errors->has('user.password') ? ' is-invalid' : '' }}" name="user[password_confirmation]">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">更新</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
