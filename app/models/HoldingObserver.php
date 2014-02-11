<?php
/*
* Observer events (created, deleted) occur Holding model. Perform necessary actions after a specific event occurs on the model.
*
*/
class HoldingObserver {

    public function created($model) {
    	// Trace::create([ 'user_id'=>Auth::user(),'action'=>trans('logs.create-Holding') ]);
    }

    public function updated($model) {

    }

}