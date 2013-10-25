<?php

class Reserve extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
		'hoss_id' => 'required',
		'user_id' => 'required',
		'description' => 'required'
	);
}
