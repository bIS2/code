<?php

class List extends Eloquent {
	protected $guarded = array();

  public static function boot() {
    parent::boot();
		List::observe(new ListObserver);
  }


	public static $rules = array(
		'name' => 'required',
		'user_id' => 'required'
	);

	public function holdingssets(){
		return $this->belongsToMany('Holdings')->withTimestamps();;
	}
  
  public function user() {
  	return $this->belongsTo('User');
  }

}
