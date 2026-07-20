<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->sort ?? 'created_at';
        $order = $request->order ?? 'desc';

        $users = User::select('users.*')
            ->addSelect([
                'role' => Role::select('name')
                    ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
                    ->whereColumn('model_has_roles.model_id', 'users.id')
                    ->where('model_has_roles.model_type', User::class)
                    ->orderBy('roles.id')
                    ->limit(1)
            ])
            ->where('email', 'like', "%{$request->q}%")
            ->orWhere('name', 'like', "%{$request->q}%")
            ->orderBy($sort, $order)
            ->paginate(
                $perPage = 25,
                $columns = ['*'],
                $pageName = 'users'
            );

        $users->setPath('');

        $roles = Role::all();

        return Inertia::render('user/index', [
            'users' => $users,
            'roles' => $roles,
            "query" => $request->q ?? '',
        ]);
    }

    function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'contact' => $request->contact,
            'company' => 0
        ]);

        if ($request->role) {
            $role = Role::findByName($request->role);
            if ($user->roles->first())
                $user->removeRole($user->roles->first());
            if ($role)
                $user->assignRole($role->name);
        }

        event(new Registered($user));

        return redirect()->route('users.index', ["users" => $request->pages]);

    }

    function destroy(Request $request)
    {
        $user = User::find($request->id);
        $user->delete();

        return redirect()->route('users.index', ["users" => $request->pages]);
    }

    function update(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return redirect()->route('users.index', ["users" => $request->pages]);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'company' => 0
        ]);

        if ($request->role) {
            $role = Role::findByName($request->role);

            if ($user->roles->first())
                $user->removeRole($user->roles->first());

            if ($role)
                $user->assignRole($role->name);
        } else {
            if ($user->roles->first())
                $user->removeRole($user->roles->first());
        }

        return redirect()->route('users.index', ["users" => $request->pages]);
    }
}
