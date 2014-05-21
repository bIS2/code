<?php
/*
* Observer events (created, deleted) occur Hlist model. Perform necessary actions after a specific event occurs on the model.
*
*/
class HlistObserver {

  public function saving($model) {

    // When a list is saved in the database and has the Revisend field 1 then forming the holding list was revised to change the state
  	if ($model->revised==1){
  		foreach ( $model->holdings()->select('holdings.id','holdings.state')->whereState('ok')->orWhere( 'state','=','annotated')->get() as $holding ) {
   			$state = State::create([ 
  				'holding_id'	=> $holding->id, 
  				'state' 			=> 'revised_'.$holding->state, 
  				'user_id'			=> Auth::user()->id 
  			]);
  		}
  	}

  }

  public function updated($model){

  	if ($model->isDirty('state')){
      if ( $model->state=='received' ){
        $ids = $model->holdings()->where('holdings.state','=','delivery')->get();//select('holdings.id')->lists('id');
        echo var_dump($ids);
        die();
        //Holding::where('holdings.id','in', $ids)->update(['state'=>'received']);
      }
  	}

  }
}