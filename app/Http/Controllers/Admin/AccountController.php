<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccountFormRequest;
use App\Entities\Admin;
use Auth;
use Hash;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $me = Auth::user('admin');
        return view('admin.account.edit', ['me' => $me]);
    }

    public function update(AccountFormRequest $request)
    {
        $me = Auth::user('admin');
        $attributes = $request->input('admin');
        unset($attributes['password_confirmation']);
        foreach ($attributes as $key => $val) {
            if ($key === 'password') {
                $attributes[$key] = Hash::make($val);
            }
            if (empty($val)) {
                unset($attributes[$key]);
            }
        }

        $me->fill($attributes)->save();

        return redirect('/admin/account')->with(['flash_message' => '更新しました']);
    }
}
