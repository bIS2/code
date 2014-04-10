<?php

class Received extends Eloquent {
	protected $guarded = array();

  public static function boot() {
    parent::boot();
    Received::observe(new ReceivedObserver);
  }

	public static $rules = array(
		'holding_id' => 'required',
		'user_id' => 'required'
	);

  public function user() {
      return $this->belongsTo('User');
  }

  public function holding() {
      return $this->belongsTo('Holding');
  }


}
