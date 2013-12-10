<?php

return [

    'initialize' => function($authority) {

        $user = Auth::guest() ? new User : $authority->getCurrentUser();

        // Allow delivery holding from storage revision
        $authority->allow('delivery', 'Holding', function($self, $holding) {
          return ( $holding->is_revised && $holding->is_correct && Auth::user()->hasRole('postuser') );
        });

        $authority->allow('revise', 'Holding', function($self, $holding) {
          return ( (Auth::user()->hasRole('magvuser') || Auth::user()->hasRole('maguser')) && !$holding->is_revised );
        });

        $authority->allow('set_size', 'Holding', function($self, $holding) {
          return ( (Auth::user()->hasRole('magvuser') || Auth::user()->hasRole('maguser')) && !$holding->is_revised );
        });

        $authority->allow('manage', 'User', function($self, $user) {
          return ($holding->is_correct || $holding->is_annotated );
        });


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
