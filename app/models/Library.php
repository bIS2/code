<?php

class Library extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

  public function users() {  return $this->hasMany('User');  }

  public function holdings() {  return $this->hasMany('Holding');  }

  public function scopeLibraryperholding($query, $code) {
  	return $this->whereCode($code)->paginate(1);
  }	
}
