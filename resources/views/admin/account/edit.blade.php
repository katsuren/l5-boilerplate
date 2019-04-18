@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">アカウント編集</div>

                <form method="POST" action="/admin/account">
                    <div class="card-body">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">

                        <div class="form-group row">
                            <label for="admin__name" class="col-md-4 col-form-label text-md-right">氏名</label>

                            <div class="col-md-6">
                                <input id="admin__name" type="text" class="form-control{{ $errors->has('admin.name') ? ' is-invalid' : '' }}" name="admin[name]" value="{{ old('admin.name', $me->name) }}" required autofocus>

                                @if ($errors->has('admin.name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('admin.name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="admin__email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>

                            <div class="col-md-6">
                                <input id="admin__email" type="email" class="form-control{{ $errors->has('admin.email') ? ' is-invalid' : '' }}" name="admin[email]" value="{{ old('admin.email', $me->email) }}" required>

                                @if ($errors->has('admin.email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('admin.email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="admin__password" class="col-md-4 col-form-label text-md-right">パスワード</label>

                            <div class="col-md-6">
                                <input id="admin__password" type="password" class="form-control{{ $errors->has('admin.password') ? ' is-invalid' : '' }}" name="admin[password]">

                                @if ($errors->has('admin.password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('admin.password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="admin__password_confirmation" class="col-md-4 col-form-label text-md-right">パスワード（確認）</label>

                            <div class="col-md-6">
                                <input id="admin__password_confirmation" type="password" class="form-control{{ $errors->has('admin.password') ? ' is-invalid' : '' }}" name="admin[password_confirmation]">
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
