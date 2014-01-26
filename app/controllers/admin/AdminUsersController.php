<?php

class AdminUsersController extends AdminController {


    /**
     * User Model
     * @var User
     */
    protected $user;

    /**
     * Role Model
     * @var Role
     */
    protected $role;

    /**
     * Permission Model
     * @var Permission
     */
    protected $permission;

    /**
     * Permission Model
     * @var Permission
     */
    protected $data;

    /**
     * Inject the models.
     * @param User $user
     * @param Role $role
     * @param Permission $permission
     */
    public function __construct(User $user, Role $role, Permission $permission)
    {
        parent::__construct();
        $this->current_user = Auth::user();
        $this->user = $user;
        $this->role = $role;
        $this->permission = $permission;

        $this->data['roles'] = (Auth::user()->hasRole('sysadmin')) ? $this->role->whereName('superuser')->get() : $this->role->where('name','<>','sysadmin')->get();
        $this->data['libraries'] = (Auth::user()->hasRole('superuser')) ? Library::whereId( Auth::user()->library_id )->get() : Library::all();


    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        // Title
        $title = Lang::get('admin/users/title.user_management');

        // Show all users for de admin or only the user by library for de librarian
        if ( $this->current_user->hasRole('sysadmin') )
			    $users = User::with('library');
			  elseif ( $this->current_user->hasRole('superuser') )
			    $users = User::with('library')->where( 'library_id','=', $this->current_user->library_id );

        // Show the page
        if ($q = Input::has('buscar')) {

        	$users = $users
        		->where('name','like',"%$q%")
        		->orWhere('email','like',$q)
        		->orWhere('username','like',"%$q%")
        		->orWhere('lastname','like',$q);
        } 
        $users = $users->paginate(25);
        return View::make('admin/users/index', [ 'users'=>$users, 'title'=>$title ] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {

        $user = new User();

        $this->data['user'] = $user;

        $selectedRoles = Input::old('roles', array());

		// Show the page
		return View::make('admin/users/create_edit', $this->data );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate()
    {
        $this->user->username 	= Input::get( 'username' );
        $this->user->email 		= Input::get( 'email' );
        $this->user->password 	= Input::get( 'password' );
        $this->user->library_id = Input::get( 'library_id' );

        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.
        $this->user->password_confirmation = Input::get( 'password_confirmation' );
        $this->user->confirmed = Input::get( 'confirm' );

        // Permissions are currently tied to roles. Can't do this yet.
        //$user->permissions = $user->roles()->preparePermissionsForSave(Input::get( 'permissions' ));

        // Save if valid. Password field will be hashed before save
        $this->user->save();

        if ( $this->user->id )
        {
            // Save roles. Handles updating.
            $this->user->saveRoles(Input::get( 'roles' ));

            // Redirect to the new user page
            return Redirect::to('admin/users')->with('success', Lang::get('admin/users/messages.create.success'));
        }
        else
        {
            // Get validation errors (see Ardent package)
            $error = $this->user->errors()->all();

            return Redirect::to('admin/users/create')
                ->withInput(Input::except('password'))
                ->with( 'error', $error );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $user
     * @return Response
     */
    public function getShow($user)
    {
        // redirect to the frontend
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $user
     * @return Response
     */
    public function getEdit($id)  {

    	$user = User::find($id);
        $this->data['user'] = $user;
        
        if ( $user->id )
        {
            $roles = $this->role->all();
            $permissions = $this->permission->all();

            // Title
        	$title = Lang::get('admin/users/title.user_update');
        	// mode
        	$mode = 'edit';

        	return View::make('admin/users/create_edit', $this->data );
        }
        else
        {
            return Redirect::to('admin/users')->with('error', Lang::get('admin/users/messages.does_not_exist'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $user
     * @return Response
     */
    public function postEdit($user)
    {
        // Validate the inputs
        $validator = Validator::make(Input::all(), $user->getUpdateRules());


        if ($validator->passes())
        {
            $oldUser = clone $user;
            $user->username = Input::get( 'username' );
            $user->email = Input::get( 'email' );
            $user->confirmed = Input::get( 'confirm' );
            $user->name = Input::get( 'name' );
            $user->lastname = Input::get( 'lastname' );
            $user->library_id = Input::get( 'library_id' );

            $password = Input::get( 'password' );
            $passwordConfirmation = Input::get( 'password_confirmation' );

            if(!empty($password)) {
                if($password === $passwordConfirmation) {
                    $user->password = $password;
                    // The password confirmation will be removed from model
                    // before saving. This field will be used in Ardent's
                    // auto validation.
                    $user->password_confirmation = $passwordConfirmation;
                } else {
                    // Redirect to the new user page
                    return Redirect::to('admin/users/' . $user->id . '/edit')->with('error', Lang::get('admin/users/messages.password_does_not_match'));
                }
            } else {
                unset($user->password);
                unset($user->password_confirmation);
            }
            
            if($user->confirmed == null) {
                $user->confirmed = $oldUser->confirmed;
            }

            $user->prepareRules($oldUser, $user);

            // Save if valid. Password field will be hashed before save
            $user->amend();

            // Save roles. Handles updating.
            $user->saveRoles(Input::get( 'roles' ));
        }

        // Get validation errors (see Ardent package)
        $error = $user->errors()->all();

        if(empty($error)) {
            // Redirect to the new user page
            return Redirect::to('admin/users/edit/'.$user->id)->with('success', Lang::get('admin/users/messages.edit.success'));
        } else {
            return Redirect::to('admin/users/' . $user->id . '/edit')->with('error', Lang::get('admin/users/messages.edit.failure'));
        }
    }

    /**
     * Remove user page.
     *
     * @param $user
     * @return Response
     */
    public function getDelete($user)
    {
        // Title
        $title = Lang::get('admin/users/title.user_delete');

        // Show the page
        return View::make('admin/users/delete', compact('user', 'title'));
    }

    /**
     * Remove the specified user from storage.
     *
     * @param $user
     * @return Response
     */
    public function postDelete($user)
    {
        // Check if we are not trying to delete ourselves
        if ($user->id === Confide::user()->id)
        {
            // Redirect to the user management page
            return Redirect::to('admin/users')->with('error', Lang::get('admin/users/messages.delete.impossible'));
        }

        //Check if user have work disable
        if ($user->states()->exists()) {

        	$user->update(['disable'=>true]);

        } else {
	        AssignedRoles::where('user_id', $user->id)->delete();

	        $id = $user->id;
	        $user->delete();
        	
        }

        // Was the comment post deleted?
        $user = User::find($id);
        if ( empty($user) )
        {
            // TODO needs to delete all of that user's content
        		return Response::json( ['remove' => $id] );
            // return Redirect::to('admin/users')->with('success', Lang::get('admin/users/messages.delete.success'));
        } elseif ($user->disable) {
        		return Response::json( ['disabled' => $id] );
        } else {
            // There was a problem deleting the user
            return Redirect::to('admin/users')->with('error', Lang::get('admin/users/messages.delete.error'));
        }
    }

    /**
     * Show a list of all the users formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function getData()
    {
        $users = User::leftjoin('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
                    ->leftjoin('roles', 'roles.id', '=', 'assigned_roles.role_id')
                    ->select(array('users.id', 'users.username','users.email', 'roles.name as rolename', 'users.confirmed', 'users.created_at'));

        return Datatables::of($users)
        // ->edit_column('created_at','{{{ Carbon::now()->diffForHumans(Carbon::createFromFormat(\'Y-m-d H\', $test)) }}}')

        ->edit_column('confirmed','@if($confirmed)
                            Yes
                        @else
                            No
                        @endif')

        ->add_column('actions', '<a href="{{{ URL::to(\'admin/users/\' . $id . \'/edit\' ) }}}" class="iframe btn btn-xs btn-default">{{{ Lang::get(\'button.edit\') }}}</a>
                                @if($username == \'admin\')
                                @else
                                    <a href="{{{ URL::to(\'admin/users/\' . $id . \'/delete\' ) }}}" class="iframe btn btn-xs btn-danger">{{{ Lang::get(\'button.delete\') }}}</a>
                                @endif
            ')

        ->remove_column('id')

        ->make();
    }
}
