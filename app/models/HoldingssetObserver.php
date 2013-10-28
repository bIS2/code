<?php

class HoldingssetObserver {

    public function created($model) {
    	// $model->group()->holdingssets_counter =+ 1
    }

    public function saved($model)
    {
        //
    }

}