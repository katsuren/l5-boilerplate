<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AccountFormRequest;
use App\Entities\User;
use App\Notifications\VerifyUpdateEmailNotification;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Notification;

class AccountController extends Controller
{
    /**
     * @var User
     */
    protected $userRepository;

    public function __construct(
        User $userRepository
    ) {
        $this->userRepository = $userRepository;

        $this->middleware('signed')->only('verify');
    }

    public function index()
    {
        $me = Auth::user();
        return view('user.account.edit', ['me' => $me]);
    }

    public function update(AccountFormRequest $request)
    {
        $me = Auth::user();
        $userAttributes = $request->input('user');
        foreach ($userAttributes as $key => $val) {
            if ($key === 'password') {
                $userAttributes[$key] = Hash::make($val);
            }
            if (empty($val)) {
                unset($userAttributes[$key]);
            }
        }
        if ($userAttributes['email'] !== $me->email) {
            $email = $userAttributes['email'];
            unset($userAttributes['email']);
            Notification::route('mail', $email)
                ->notify(new VerifyUpdateEmailNotification($email));
        }

        $me->fill($userAttributes)->save();

        return redirect('/user/account')->with(['flash_message' => '更新しました']);
    }

    public function verify($email)
    {
        $me = Auth::user();
        $email = rawurldecode($email);
        $me->fill(['email' => $email])->save();
        return redirect('/user/account')->with(['flash_message' => 'メールアドレスを更新しました']);
    }
}
