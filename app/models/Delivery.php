<?php

class Delivery extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
		'holding_id' => 'required'
	);
}
