<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller {

	private $role;
	private $permission;

	public function __construct(Permission $permission, Role $role)
	{
		$this->permission = $permission;
		$this->role = $role;
	}

	public function index()
	{
		$permissions = $this->permission->paginate(10);
		return view('permissions.index', compact('permissions'));
	}

	public function create()
	{
		return view('permissions.create');
	}

	public function store(Request $request)
	{
		$this->validate($request, array('name' => 'required', 'display_name' => 'required', 'route' => 'required'));

		$permission = $this->permission->create($request->all());

		$role = $this->role->findBy('name', 'admin');

		$role->perms()->sync([$permission->id], false);

		return redirect('/permissions');
	}

	public function edit($id)
	{
		$permission = $this->permission->find($id);
		return view('permissions.edit', compact('permission'));
	}


	public function update(Request $request, $id)
	{
		$this->validate($request, array('name' => 'required', 'display_name' => 'required', 'route' => 'required'));

		$permission = $this->permission->find($id);
		$permission->update($request->all());

		return redirect('/permissions');
	}

	public function destroy($id)
	{
		$this->permission->delete($id);

		return redirect('/permissions');
	}

}