<?php

class Group extends Eloquent {
	protected $guarded = array();

  public static function boot() {
    parent::boot();
		Group::observe(new TraceObserver);
  }

	public static $rules = array(
		'name' => 'required',
		'user_id' => 'required'
	);

	public function holdingssets(){
		return $this->belongsToMany('Holdingsset')->withTimestamps();;
	}
  
  public function user() {
  	return $this->belongsTo('User');
  }

}
