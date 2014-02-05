<?php
/*
* Observer events (created, deleted) occur Hlist model. Perform necessary actions after a specific event occurs on the model.
*
*/
class HlistObserver {

  public function saving($model) {

    // When a list is saved in the database and has the Revisend field 1 then forming the holding list was revised to change the state
  	if ($model->revised==1){
  		foreach ( $model->holdings()->whereState('ok')->orWhere( 'state','=','annotated')->get() as $holding ) {
   			$state = State::create([ 
  				'holding_id'	=> $holding->id, 
  				'state' 			=> 'revised_'.$holding->state, 
  				'user_id'			=> Auth::user()->id 
  			]);
  		}
  	}

  }

}