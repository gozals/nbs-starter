<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use Illuminate\Http\Request;
use App\Role;
use App\User;
use DB,Datatables;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

	protected $user;

    /**
     * @param User $user
     * @param Role $role
     */
	public function __construct(User $user, Role $role)
	{
		$this->user = $user;
		$this->role = $role;
	}

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function index()
	{
		return view('users.index');
	}

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function create()
	{
		$roles = $this->role->all();
		return view('users.create', compact('roles'));
	}

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function store(UserCreateRequest $request)
	{
        $input = $request->all();
        $input['password'] = Hash::make($request->get('password')); //default password
        unset($input['password_confirmation']);
		$user = $this->user->create($input);

		if($request->get('roles'))
		{
			$user->roles()->sync($request->get('roles'));
		}
		else
		{
			$user->roles()->sync([]);
		}
	}

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function edit($id)
	{
		$user = $this->user->whereId($id)->with('roles')->first();
		$roles = $this->role->all();
		$userRoles = $user->roles();
        return response()->json($user);
	}

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function update(Request $request, $id)
	{
		$user = $this->user->find($id);

		$user->name = $request->get('name');
		$user->email = $request->get('email');
		if($request->get('password'))
		{
			$user->password = $request->get('password');
		}
		$user->save();

		if($request->get('roles'))
		{
			$user->roles()->sync($request->get('roles'));
		}
		else
		{
			$user->roles()->sync([]);
		}

        return response()->json('success');
	}

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
	public function destroy($id)
    {
		$this->user->destroy($id);

        return response()->json('success');
	}

    /**
     * @return mixed
     */
    public function data(){
        $users = User::leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
                        ->leftjoin('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select(['users.id', 'users.name', 'email', 'roles.name as role', 'users.created_at']);
        return Datatables::eloquent($users)
            ->removeColumn('id')
            ->addColumn('action',function($user){
                $button_detail ='<button value="'.$user->id.'"
                            class="btn btn-xs btn-primary tip show-detail"
                            title="Edit"><span class="glyphicon glyphicon-edit"></span></button>';
                $button_delete = '<button data-id="'.$user->id.'"
                            class="btn btn-xs btn-danger tip show-delete"
                            title="Delete"><span class="glyphicon glyphicon-remove"></span></button>';

                return '<div class="btn-group pull-right">'.$button_detail.$button_delete.'</div>';
            })
            ->make(true);
    }

}