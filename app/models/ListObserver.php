<?php

class ListObserver {

    public function created($model) {
    	// Trace::create([ 'user_id'=>Auth::user()->id,'action'=>'Creo un nuevo grupo:'.$model->name]);
    }

    public function saved($model)
    {
        //
    }

}