<?php

class Holdingsset extends Eloquent {
	protected $guarded = array();
	public $timestamps = false;

	public static $rules = array();

    public function holdings() {
        return $this->hasMany('Holding');
    }

}
