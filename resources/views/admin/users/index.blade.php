@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h5>ユーザー管理</h5>

            <div class="card">
                <div class="card-header">検索</div>
                <form method="GET" action="/admin/users">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="s__user__name" class="col-md-4 col-form-label text-md-right">氏名</label>
                            <div class="col-md-6">
                                <input id="s__user__name" type="text" class="form-control" name="s[name]" value="{{ request('s.name') }}" autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="s__user__email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>
                            <div class="col-md-6">
                                <input id="s__user__email" type="text" class="form-control" name="s[email]" value="{{ request('s.email') }}">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">検索</button>
                            </div>
                        </div>
                    </div>
                </form>

                @if (empty($users) || $users->isEmpty())
                    対象データは存在しません
                @else
                    <div class="card-header">一覧</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>名前</th>
                                        <th>メールアドレス</th>
                                        <th>作成日</th>
                                        <th> - </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td><a href="/admin/users/{{ $user->id }}/edit">{{ $user->id }}</a></td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->created_at }}</td>
                                            <td>
                                                <form method="POST" action="/admin/users/{{ $user->id }}">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-danger"
                                                        onsubmit="return confirm('ID:{{ $user->id }} を削除してもよろしいですか？');">
                                                        削除
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @include('share.pager', ['pager' => $users])
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
