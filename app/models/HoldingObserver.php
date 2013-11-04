<?php

class HoldingObserver {

    public function created($model) {
    	Trace::create([ 'user_id'=>Auth::user(),'action'=>trans('logs.create-Holding') ]);
    }

    public function saved($model)
    {
        //
    }

}