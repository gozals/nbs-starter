<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller {

	private $role;
	private $permission;

	public function __construct(Role $role, Permission $permission)
	{
		$this->role = $role;
		$this->permission = $permission;
	}

	public function index()
	{
		return view('roles.index');
	}

    public function getList()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

	public function create()
	{
		$permissions = $this->permission->all();
		return view('roles.create', compact('permissions'));
	}

	public function store(Request $request)
	{

		$this->validate($request, array('name' => 'required', 'display_name' => 'required', 'level' => 'required|unique:roles'));

		$role = $this->role->create($request->all());

		$role->savePermissions($request->get('perms'));

		return redirect('/roles');
	}

	public function edit($id)
	{
		$role = $this->role->find($id);
		if($role->id == 1)
		{
			abort(403);
		}
		$permissions = $this->permission->all();
		$rolePerms = $role->perms();
		return view('roles.edit', compact('role', 'permissions', 'rolePerms'));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, array('name' => 'required', 'display_name' => 'required', 'level' => 'required'));

		$role = $this->role->find($id);
		$role->update($request->all());

		$role->savePermissions($request->get('perms'));

		return redirect('/roles');
	}

	public function destroy($id)
	{
		if($id == 1)
		{
			abort(403);
		}

		$this->role->delete($id);


		return redirect('/roles');
	}

}