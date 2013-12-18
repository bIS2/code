<?php

class HoldingssetObserver {

    public function created($model) {
    	//Trace::create([ 'user_id'=>Auth::user(),'action'=>trans('logs.create-Holdings Set') ]);
    }

    public function saved($model)
    {
        //
    }

}