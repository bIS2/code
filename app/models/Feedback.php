<?php

class Feedback extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
		'user_id' => 'required',
		'client' => 'required',
		'content' => 'required'
	);
}
