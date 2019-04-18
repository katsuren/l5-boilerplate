@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">管理者ユーザー作成</div>

                <form method="POST" action="/admin/admins">
                    <div class="card-body">
                        @csrf

                        <div class="form-group row">
                            <label for="admin__name" class="col-md-4 col-form-label text-md-right">氏名</label>

                            <div class="col-md-6">
                                <input id="admin__name" type="text" class="form-control{{ $errors->has('admin.name') ? ' is-invalid' : '' }}" name="admin[name]" value="{{ old('admin.name') }}" required autofocus>

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
                                <input id="admin__email" type="email" class="form-control{{ $errors->has('admin.email') ? ' is-invalid' : '' }}" name="admin[email]" value="{{ old('admin.email') }}" required>

                                @if ($errors->has('admin.email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('admin.email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">新規作成</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
