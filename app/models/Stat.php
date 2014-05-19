<?php

class Stat extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
		'hodings_count' => 'required',
		'sets_count' => 'required',
		'sets_grouped' => 'required'
	);
}
