<?php

class Tag extends Eloquent {
	protected $guarded = array();
	public $timestamps = false;

	public static $rules = array(
		'name' => 'required'
	);

  public function holdings() {
      return $this->belongsToMany('Holding')->withPivot('content');
  }

}
