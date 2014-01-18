<?php

class State extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
		// 'holding_id' => 'required',
		// 'user_id' => 'required',
		// 'state' => 'required'
	);

  public static function boot() {
    parent::boot();
		State::observe(new StateObserver);
  }

  public function holding() {
      return $this->belongsTo('Holding');
  }	
  public function user() {
      return $this->belongsTo('User');
  }  
}
