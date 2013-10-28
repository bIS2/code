<?php

class Comment extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
		'holding_id' => 'required',
		'category_id' => 'required',
		'user_id' => 'required',
		'comments' => 'required'
	);
}
