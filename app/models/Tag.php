<?php

class Tag extends Eloquent {
	protected $guarded = array();
	public $timestamps = false;

	public static $rules = array(
		'name' => 'required'
	);

  public function notes() {
      return $this->hasMany('Note');
  }

}
