<?php

class Hlist extends Eloquent {
	protected $guarded = array();
	protected $table = 'lists';

  public static function boot() {
    parent::boot();
	//	List::observe(new ListObserver);
  }


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
