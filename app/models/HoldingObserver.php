<?php
/*
* Observer events (created, deleted) occur Holding model. Perform necessary actions after a specific event occurs on the model.
*
*/
class HoldingObserver {

    public function created($model) {
    	// Trace::create([ 'user_id'=>Auth::user(),'action'=>trans('logs.create-Holding') ]);
    }

    public function updating($model) {

        //updates stats in library
    	if ($model->isDirty('state')){
    		$states = ['ok','annotated','revised_ok','revised_annotated'];

            if ( in_array($model->state,$states) )
                $model->library->increment('holdings_'.$model->state);

            if ( in_array($model->getOriginal('state'), $states) )
                $model->library->decrement('holdings_'.$model->getOriginal('state'));
    	}
    	
    }

}