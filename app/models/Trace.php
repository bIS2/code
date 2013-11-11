<?php

class Trace extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

  public function user() {
      return $this->belongsTo('User');
  }

  public function	scopeLastest($query){
  	return $query->orderBy('created_at','desc')->take(10);
  }

}
