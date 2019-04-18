@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">ユーザー編集</div>

                <form method="POST" action="/admin/users/{{ $user->id }}">
                    <div class="card-body">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">

                        <div class="form-group row">
                            <label for="user__name" class="col-md-4 col-form-label text-md-right">氏名</label>

                            <div class="col-md-6">
                                <input id="user__name" type="text" class="form-control{{ $errors->has('user.name') ? ' is-invalid' : '' }}"
                                    name="user[name]" value="{{ old('user.name', $user->name) }}" required autofocus>

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
                                <input id="user__email" type="email" class="form-control{{ $errors->has('user.email') ? ' is-invalid' : '' }}"
                                    name="user[email]" value="{{ old('user.email', $user->email) }}" required>

                                @if ($errors->has('user.email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('user.email') }}</strong>
                                    </span>
                                @endif
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
