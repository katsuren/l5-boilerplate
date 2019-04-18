<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SearchTrait;
use App\Http\Requests\Admin\AdminsFormRequest;
use App\Entities\Admin;
use App\Notifications\Admin\RegisterNotification;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Notification;

class AdminsController extends Controller
{
    use SearchTrait;

    /**
     * @var Admin
     */
    protected $adminRepository;

    public function __construct(Admin $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function index(Request $request)
    {
        $searchConditions = $request->input('s', []);
        $searchOptions = [
            'limit' => $request->input('limit', 50),
            'orderBy' => $request->input('orderBy', 'id'),
            'sortedBy' => $request->input('sortedBy', 'desc'),
        ];
        $admins = $this->getPager($this->adminRepository, $searchConditions, $searchOptions);

        return view('admin.admins.index', [
            'admins' => $admins,
        ]);
    }

    public function create()
    {
        return view('admin.admins.edit');
    }

    public function store(AdminsFormRequest $request)
    {
        $adminAttributes = $request->input('admin');
        $password = Str::random(12);
        $adminAttributes['password'] = Hash::make($password);

        $admin = $this->adminRepository->create($adminAttributes);
        Notification::send($admin, new RegisterNotification($password));

        return redirect('/admin/admins')->with('flash_message', 'ユーザーを作成しました');
    }

    public function destroy($id)
    {
        $admin = $this->adminRepository->findOrFail($id);
        $admin->delete();
        return redirect('/admin/admins')->with('flash_message', 'ユーザーを削除しました');
    }
}
