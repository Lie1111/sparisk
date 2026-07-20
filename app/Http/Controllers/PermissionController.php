<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->sort ?? 'created_at';
        $order = $request->order ?? 'desc';

        $permissions = Permission::where('name', 'like', "%{$request->q}%")->orderBy($sort, $order)->paginate(
            $perPage = 25,
            $columns = ['*'],
            $pageName = 'permissions'
        );

        $permissions->setPath('');

        return Inertia::render('permission/index', [
            'permissions' => $permissions,
            "query" => $request->q ?? '',
        ]);
    }
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        $permission = Permission::create([
            'name' => $request->name,
        ]);

        $role = Role::findByName('Super Admin');
        $role->givePermissionTo($request->name);

        return redirect()->route('permissions.index', ['permissions' => $request->pages]);
    }

    public function update(Request $request)
    {
        $permission = Permission::find($request->id);

        if (!$permission) {
            return redirect()->route('permissions.index', ['permissions' => $request->pages]);
        }

        $permission->update([
            'name' => $request->name,
        ]);

        return redirect()->route('permissions.index', ['permissions' => $request->pages]);
    }

    function destroy(Request $request)
    {
        $permission = Permission::find($request->id);
        $permission->delete();

        return redirect()->route('permissions.index', ['permissions' => $request->pages]);
    }
}
