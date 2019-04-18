<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SearchTrait;
use App\Http\Requests\Admin\UsersFormRequest;
use App\Entities\User;
use Auth;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    use SearchTrait;

    /**
     * @var User
     */
    protected $userRepository;

    public function __construct(
        User $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $searchConditions = $request->input('s', []);
        $searchOptions = [
            'limit' => $request->input('limit', 50),
            'orderBy' => $request->input('orderBy', 'id'),
            'sortedBy' => $request->input('sortedBy', 'desc'),
        ];
        $users = $this->getPager($this->userRepository, $searchConditions, $searchOptions);

        return view('admin.users.index', [
            'users' => $users,
        ]);
    }

    // public function show(Request $request, $id)
    // {
    //     $user = $this->userRepository->findOrFail($id);
    //     return view('admin.users.show', [
    //         'user' => $user,
    //     ]);
    // }

    public function edit($id)
    {
        $user = $this->userRepository->findOrFail($id);
        return view('admin.users.edit', [
            'isCreate' => false,
            'user' => $user,
        ]);
    }

    public function update(UsersFormRequest $request, $id)
    {
        $user = $this->userRepository->findOrFail($id);
        $userAttributes = $request->input('user');
        foreach ($userAttributes as $key => $val) {
            if ($val === '' || is_null($val) || $key === 'password') {
                unset($userAttributes[$key]);
                continue;
            }
        }
        $user->fill($userAttributes);
        $user->save();

        return redirect('/admin/users/' . $id . '/edit')->with('flash_message', 'ユーザーを更新しました');
    }

    public function destroy($id)
    {
        $user = $this->userRepository->findOrFail($id);
        $user->delete();
        return redirect('/admin/users')->with('flash_message', 'ユーザーを削除しました');
    }
}
