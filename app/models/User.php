<?php
/*
* Represents the table Users in the database, relationships, methods and attributes.
*/

use Zizaco\Confide\ConfideUser;
use Zizaco\Confide\Confide;
use Zizaco\Confide\ConfideEloquentRepository;
use Zizaco\Entrust\HasRole;
use Robbo\Presenter\PresentableInterface;
use Carbon\Carbon;

class User extends ConfideUser implements PresentableInterface {
    use HasRole;

    public static $rules = array(
        'username' => 'required|alpha_dash|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required|between:4,11|confirmed',
        'password_confirmation' => 'between:4,11',
        'roles' => 'required',
    );  

	public function hlists(){
		return $this->hasMany('Hlist');
	}

	public function groups(){
		return $this->hasMany('Group');
	}

  public function traces(){
      return $this->hasMany('Trace');
  }

	public function oks(){
		return $this->hasMany('Ok');
	}

	public function deliveries(){
		return $this->hasMany('Delivery');
	}

  public function confirms() {
      return $this->hasMany('Confirm');
  }

  public function incorrects() {
      return $this->hasMany('Incorrects');
  }

  public function library() {
      return $this->belongsTo('Library');
  }

  public function lockeds() {
      return $this->hasMany('Locked');
  }  

  public function feedbacks() {
      return $this->hasMany('Feedback');
  }

  public function states() {
      return $this->hasMany('State');
  }

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';


    public function scopeWorkers($query){
        return $query->orderBy('username')->roles()->where('roles.name', 'maguser');
    }

    public function getPresenter()
    {
        return new UserPresenter($this);
    }

    /**
     * Get user by username
     * @param $username
     * @return mixed
     */
    public function getUserByUsername( $username )
    {
        return $this->where('username', '=', $username)->first();
    }

    /**
     * Get the date the user was created.
     *
     * @return string
     */
    public function joined()
    {
        return String::date(Carbon::createFromFormat('Y-n-j G:i:s', $this->created_at));
    }

    /**
     * Save roles inputted from multiselect
     * @param $inputRoles
     */
    public function saveRoles($inputRoles)
    {
        if(! empty($inputRoles)) {
            $this->roles()->sync($inputRoles);
        } else {
            $this->roles()->detach();
        }
    }

    /**
     * Returns user's current role ids only.
     * @return array|bool
     */
    public function currentRoleIds()
    {
        $roles = $this->roles;
        $roleIds = false;
        if( !empty( $roles ) ) {
            $roleIds = array();
            foreach( $roles as &$role )
            {
                $roleIds[] = $role->id;
            }
        }
        return $roleIds;
    }

    /**
     * Redirect after auth.
     * If ifValid is set to true it will redirect a logged in user.
     * @param $redirect
     * @param bool $ifValid
     * @return mixed
     */
    public static function checkAuthAndRedirect($redirect, $ifValid=false)
    {
        // Get the user information
        $user = Auth::user();
        $redirectTo = false;

        if(empty($user->id) && ! $ifValid) // Not logged in redirect, set session.
        {
            Session::put('loginRedirect', $redirect);
            $redirectTo = Redirect::to('user/login')
                ->with( 'notice', Lang::get('user/user.login_first') );
        }
        elseif(!empty($user->id) && $ifValid) // Valid user, we want to redirect.
        {
            $redirectTo = Redirect::to($redirect);
        }

        return array($user, $redirectTo);
    }

    public function currentUser()
    {
        return (new Confide(new ConfideEloquentRepository()))->user();
    }

    public function getActived()
    {
        return  'No';
    }

    public function activated()
    {
        return  ($this->confirmed) ? Lang::get('general.yes') : Lang::get('general.no');
    }

    public function getDisabledAttribute()
    {
        return  ($this->disable) ? Lang::get('general.yes') : Lang::get('general.no');
    }


	//public function afterSave($success=true, $forced = false){ return null;}
}
