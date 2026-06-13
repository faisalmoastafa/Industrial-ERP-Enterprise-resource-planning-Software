<?php

namespace Modules\User\Http\Controllers;

use Modules\User\DataTables\UsersDataTable;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Upload\Entities\Upload;

class UsersController extends Controller
{
    public function index(UsersDataTable $dataTable) {
        abort_if(Gate::denies('access_user_management'), 403);

        return $dataTable->render('user::users.index');
    }


    public function create() {
        abort_if(Gate::denies('access_user_management'), 403);

        return view('user::users.create');
    }


    public function store(Request $request) {
        abort_if(Gate::denies('access_user_management'), 403);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:255|confirmed',
            'role' => 'required|string|exists:roles,name',
            'is_active' => 'required|integer|in:1,2',
        ]);

        abort_if($request->role === 'Super Admin' && !auth()->user()->hasRole('Super Admin'), 403);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => $request->is_active
        ]);

        $user->assignRole($request->role);

        Log::channel('security')->info('User created', [
            'user_id' => auth()->id(),
            'target_user_id' => $user->id,
            'target_email' => $user->email,
            'role' => $request->role,
            'is_active' => $request->is_active,
        ]);

        if ($request->has('image')) {
            $tempFile = Upload::where('folder', $request->image)->first();

            if ($tempFile) {
                $user->addMedia(Storage::path('temp/' . $request->image . '/' . $tempFile->filename))->toMediaCollection('avatars');

                Storage::deleteDirectory('temp/' . $request->image);
                $tempFile->delete();
            }
        }

        toast("User Created & Assigned '$request->role' Role!", 'success');

        return redirect()->route('users.index');
    }


    public function edit(User $user) {
        abort_if(Gate::denies('access_user_management'), 403);
        abort_if($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin'), 403);

        return view('user::users.edit', compact('user'));
    }


    public function update(Request $request, User $user) {
        abort_if(Gate::denies('access_user_management'), 403);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|string|exists:roles,name',
            'is_active' => 'required|integer|in:1,2',
        ]);

        abort_if($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin'), 403);
        abort_if($request->role === 'Super Admin' && !auth()->user()->hasRole('Super Admin'), 403);

        $oldRoles = $user->roles()->pluck('name')->values()->all();

        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'is_active' => $request->is_active
        ]);

        $user->syncRoles($request->role);

        Log::channel('security')->info('User updated', [
            'user_id' => auth()->id(),
            'target_user_id' => $user->id,
            'target_email' => $user->email,
            'old_roles' => $oldRoles,
            'new_role' => $request->role,
            'is_active' => $request->is_active,
        ]);

        if ($request->has('image')) {
            $tempFile = Upload::where('folder', $request->image)->first();

            if ($user->getFirstMedia('avatars')) {
                $user->getFirstMedia('avatars')->delete();
            }

            if ($tempFile) {
                $user->addMedia(Storage::path('temp/' . $request->image . '/' . $tempFile->filename))->toMediaCollection('avatars');

                Storage::deleteDirectory('temp/' . $request->image);
                $tempFile->delete();
            }
        }

        toast("User Updated & Assigned '$request->role' Role!", 'info');

        return redirect()->route('users.index');
    }


    public function destroy(User $user) {
        abort_if(Gate::denies('access_user_management'), 403);
        abort_if($user->hasRole('Super Admin'), 403);

        $userData = [
            'user_id' => auth()->id(),
            'target_user_id' => $user->id,
            'target_email' => $user->email,
            'roles' => $user->roles()->pluck('name')->values()->all(),
        ];

        $user->delete();

        Log::channel('security')->warning('User deleted', $userData);

        toast('User Deleted!', 'warning');

        return redirect()->route('users.index');
    }
}
