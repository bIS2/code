<?php

class Comments_category extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
		'name' => 'required'
	);
}
