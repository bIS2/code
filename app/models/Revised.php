<?php

class Revised extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
		'holding_id' => 'required',
		'user_id' => 'required'
	);

  public static function boot() {
    parent::boot();
    // Delivery::observe(new SetUserObserver);
  }

  public function holding() {
      return $this->belongsTo('Holding');
  }

  public function user() {
      return $this->belongsTo('user');
  }
	
}
