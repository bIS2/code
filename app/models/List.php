<?php

class List extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
		'name' => 'required',
		'user_id' => 'required'
	);
}
