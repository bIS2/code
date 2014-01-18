<?php

class HlistObserver {

  public function saving($model) {

  	if ($model->revised==1){
  		foreach ( $model->holdings()->get() as $holding ) {

   			$state = State::create([ 
  				'holding_id'	=> $holding->id, 
  				'state' 			=> 'revised_'.$holding->state, 
  				'user_id'			=> Auth::user()->id 
  			]);

  		}
  	}

  }

}