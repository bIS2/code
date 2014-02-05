<?php
/*
* Represents the Role State in the database, relationships, methods and attributes.
*/

use Zizaco\Entrust\EntrustRole;
use Robbo\Presenter\PresentableInterface;

class Role extends EntrustRole implements PresentableInterface
{

    /**
     * Same presenter as the user model.
     * @return Robbo\Presenter\Presenter|UserPresenter
     */
    public function getPresenter()
    {
        return new UserPresenter($this);
    }

    public function getFullName() {
    	return ($this->description) ? $this->name.'-'.$this->description : $this->name;


    }

    /**
     * Provide an array of strings that map to valid roles.
     * @param array $roles
     * @return stdClass
     */


    public function validateRoles( array $roles )
    {
        $user = Confide::user();
        $roleValidation = new stdClass();
        foreach( $roles as $role )
        {
            // Make sure theres a valid user, then check role.
            $roleValidation->$role = ( empty($user) ? false : $user->hasRole($role) );
        }
        return $roleValidation;
    }
}