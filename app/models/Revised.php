<?php

class Revised extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
		'holding_id' => 'required',
		'user_id' => 'required'
	);

  public static function boot() {
    parent::boot();
    Revised::observe(new RevisedObserver);
  }

  public function holding() {
      return $this->belongsTo('Holding');
  }

  public function user() {
      return $this->belongsTo('User');
  }
	
}
