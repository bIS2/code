<?php

class State extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
		'holding_id' => 'required',
		'user_id' => 'required',
		'state' => 'required'
	);

  public function holding() {
      return $this->belongsTo('Holding');
  }	
  public function user() {
      return $this->belongsTo('User');
  }  
}
