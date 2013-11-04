<?php

class Library extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

  public function users() {  return $this->hasMany('User');  }	
}
