<?php

class Cabinet extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
		'name' => 'required',
		'user_id' => 'required'
	);

	public function holdings(){
		return $this->belongsToMany('Holding')->withTimestamps();;
	}
  
  public function user() {
  	return $this->belongsTo('User');
  }
  

}
