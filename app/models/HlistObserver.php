<?php
/*
* Observer events (created, deleted) occur Hlist model. Perform necessary actions after a specific event occurs on the model.
*
*/
class HlistObserver {

  public function saving($model) {

    // When a list is saved in the database and has the Revisend field 1 then forming the holding list was revised to change the state
  	if ( $model->isDirty('revised') && ($model->revised==1) ){
  		foreach ( $model->holdings()->select('holdings.id','holdings.state')->where('holdings.state','=','ok')->orWhere( 'holdings.state','=','annotated')->get() as $holding ) {
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

        Holding::where('holdings.state','=','delivery')->whereIn('holdings.id',function($query) use ($model){
          $query->from('hlists')
            ->select('hlist_holding.holding_id')
            ->join('hlist_holding', 'hlist_holding.hlist_id','=','hlists.id')
            ->where('hlists.id',$model->id);
        })->update(['state'=>'received']);

      }
  	}

  }
}