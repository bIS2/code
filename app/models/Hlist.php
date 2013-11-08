<?php

class Hlist extends Eloquent {
	protected $guarded = array();

  public static function boot() {
    parent::boot();
		Hlist::observe(new TraceObserver);
  }


	public static $rules = array(
		'name' => 'required',
		'user_id' => 'required'
	);

	public function holdings(){
		return $this->belongsToMany('Holding');
	}
  
  public function user() {
  	return $this->belongsTo('User');
  }

}
