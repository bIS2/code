<?php

class DeliveryObserver {

  public function created($model) {

  	$holding = Holding::find($model->holding_id);
  	if ($holding->is_annotated)
  		Confirm::whereHoldingssetId( $holding->holdingsset_id )->delete();

  	// Set related holding like delivered
  	Holding::whereIn('holdings.id',$model->hlist->holdings()->select('holdings.id')->lists('id'))->update( [ 'delivered' => true ] );

  }

  public function deleted($model){
  	// Set related holding like NOT delivered
  	Holding::whereIn('holdings.id',$model->hlist->holdings()->select('holdings.id')->lists('id'))->update( [ 'delivered' => false ] );
  }

}