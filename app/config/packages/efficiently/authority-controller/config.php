<?php

return [

    'initialize' => function($authority) {

        $user = Auth::guest() ? new User : Auth::user();

      	$authority->allow('create','Hlist', function($self,$user){
      		return (Auth::user()->hasRole('magvuser') || Auth::user()->hasRole('bibuser') );
      	});	

        $authority->allow('delivery', 'Hlist', function($self,$hlist) {
          return  (Auth::user()->hasRole('postuser'));
        });

        $authority->allow('comment', 'Holding', function($self,$holding) {
          return  ( (!$holding->was_delivery && Auth::user()->hasRole('postuser')) || Auth::user()->hasRole('speichuser') ) ;
        });

        $authority->allow('view_comment', 'Holding', function($self,$holding) {
          return  ( Auth::user()->hasRole('magvuser') && ($holding->state=='commented') ) ;
        });


        $authority->allow('revise', 'Hlist', function($self, $hlist) {
          return ( $hlist->is_finish && Auth::user()->hasRole('maguser') && !$hlist->revised  );
        });

        $authority->allow('receive', 'Holding', function($self, $holding) {
          return ( $holding->was_delivery && !$holding->is_received && Auth::user()->hasRole('speichuser') );
        });

        $authority->allow('trash', 'Holding', function($self, $holding) {
          return ( $holding->whereState('spare') && Auth::user()->hasRole('bibuser') );
        });

        $authority->allow('burn', 'Holding', function($self, $holding) {
          return (  $holding->is_trashed && Auth::user()->hasRole('magvuser') && !$holding->is_burned );
        });

        if (Auth::user()->hasRole('magvuser') || Auth::user()->hasRole('maguser')){
            $authority->allow('revise', 'Holding', function($self, $holding) {
              return ( !$holding->is_revised );
            });
        }

        $authority->allow('set_size', 'Holding', function($self, $holding) {
          return ( ( Auth::user()->hasRole('magvuser') || Auth::user()->hasRole('maguser') )  && !$holding->is_revised );
        });

        $authority->allow('work', 'Holding', function($self, $holding) {
          return ( Auth::user()->hasRole('magvuser') || Auth::user()->hasRole('maguser') || Auth::user()->hasRole('speichuser') || Auth::user()->hasRole('postuser') );
        });

        $authority->allow('touch', 'Holding', function($self, $holding) {
          return (( Auth::user()->hasRole('magvuser') || Auth::user()->hasRole('maguser') ) && ($holding->is_correct || $holding->is_annotated || $holding->is_confirmed) && Input::has('hlist_id'));
        });

        $authority->allow('manage', 'User', function($self, $user) {
          return ($holding->is_correct || $holding->is_annotated );
        });

        $authority->allow('manage','Feedback', function($self,$user) {
          return Auth::user()->hasRole('superuser');
        });

        $authority->allow('stats_store','Feedback', function($self, $user) {
          return !Auth::guest() && Auth::user()->hasRole('magvuser') && Auth::user()->hasRole('maguser');
        });

        $authority->allow('delete','User', function($self, $user) {
        	return ( ( $user->hasRole('superuser') && Auth::user()->hasRole('sysadmin') ) || ( !$user->hasRole('sysadmin') && Auth::user()->hasRole('superuser') && ($user->library_id == Auth::user()->library_id)));
        });

        if ($user->hasRole('magvuser') || $user->hasRole('maguser'))
        	$authority->allow('btn-mag');

        if ($user->hasRole('magvuser') || $user->hasRole('bibuser'))
        	$authority->allow('btn-v');

        // Action aliases. For example:
        //
        // $authority->addAlias('moderate', ['read', 'update', 'delete']);
        //
        // See the wiki of CanCan for details:
        // https://github.com/ryanb/cancan/wiki/Action-Aliases
        //
        // Define abilities for the passed in user here. For example:
        //
        //  $user = Auth::guest() ? new User : $authority->getCurrentUser();
        //  if ($user->hasRole('admin')) {
        //      $authority->allow('manage', 'all');
        //  } else {
        //      $authority->allow('read', 'all');
        //  }
        //
        // The first argument to `allow` is the action you are giving the user
        // permission to do.
        // If you pass 'manage' it will apply to every action. Other common actions
        // here are 'read', 'create', 'update' and 'destroy'.
        //
        // The second argument is the resource the user can perform the action on.
        // If you pass 'all' it will apply to every resource. Otherwise pass a Eloquent
        // class name of the resource.
        //
        // The third argument is an optional anonymous function (Closure) to further filter the
        // objects.
        // For example, here the user can only update available products.
        //
        //  $authority->allow('update', 'Product', function($self, $product) {
        //      return $product->available === true;
        //  });
        //
        // See the wiki of CanCan for details:
        // https://github.com/ryanb/cancan/wiki/Defining-Abilities
        //
        // Loop through each of the users permissions, and create rules:
        //
        // foreach($user->permissions as $perm) {
        //  if ($perm->type == 'allow') {
        //      $authority->allow($perm->action, $perm->resource);
        //  } else {
        //      $authority->deny($perm->action, $perm->resource);
        //  }
        // }
        //

    }

];
