<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->sort ?? 'created_at';
        $order = $request->order ?? 'desc';

        $roles = Role::where('name', 'like', "%{$request->q}%")->with(['permissions'])->orderBy($sort, $order)->paginate(
            $perPage = 25,
            $columns = ['*'],
            $pageName = 'roles'
        );

        $roles->setPath('');

        $allPermissions = Permission::all();

        return Inertia::render('role/index', [
            'roles' => $roles,
            'allPermissions' => $allPermissions,
            "query" => $request->q ?? '',
        ]);
    }

    public function create(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $role = Role::create([
            'name' => $request->name,
        ]);

        return redirect()->route('roles.index', ["rolePermissions" => $request->pages]);
    }

    public function update(Request $request)
    {
        $role = Role::find($request->id);

        if (!$role) {
            return redirect()->route('roles.index', ["rolePermissions" => $request->pages]);
        }

        if ($request->type === 'setPermissions') {
            if (count($request->permissions) > 0)
                $role->syncPermissions($request->permissions);

            return redirect()->route('roles.index', ["rolePermissions" => $request->pages]);
        }

        $role->update([
            'name' => $request->name,
        ]);

        return redirect()->route('roles.index', ["rolePermissions" => $request->pages]);
    }

    function destroy(Request $request)
    {
        $role = Role::find($request->id);
        $role->delete();

        return redirect()->route('roles.index', ["rolePermissions" => $request->pages]);
    }
}
