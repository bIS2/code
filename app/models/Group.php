<?php

class Group extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
		'name' => 'required',
		'user_id' => 'required'
	);
}
