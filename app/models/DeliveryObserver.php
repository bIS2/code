<?php

class DeliveryObserver {

  public function created($model) {

  	$holding = Holding::find($model->holding_id);
  	if ($holding->is_annotated)
  		Confirm::whereHoldingssetId( $holding->holdingsset_id )->delete();

  }

}