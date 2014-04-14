<?php

class ReceivedObserver {

    public function created($model) {
	  	// Set related holding like received
	  	Holding::whereId( $model->holding_id )->update( [ 'received' => true ] );

    }

    public function deleted($model) {

	  	// Set related holding like NOT received
	  	Holding::whereId( $model->holding_id )->update( [ 'received' => false ] );

    }

}
