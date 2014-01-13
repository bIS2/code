<?php

class Delivery extends Eloquent {
	protected $guarded = array();

  public static function boot() {
    parent::boot();
    Delivery::observe(new SetUserObserver);
    Delivery::observe(new DeliveryObserver);
  }

	public static $rules = array(
		'hlist_id' => 'required'
	);

  public function hlist() {
      return $this->belongsTo('Hlist');
  }

  public function user() {
      return $this->belongsTo('user');
  }

}
