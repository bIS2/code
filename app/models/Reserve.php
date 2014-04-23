<?php
/*
* Represents the table Reserve in the database, relationships, methods and attributes. Represents holdings are reserved by the Magvuser
*/

class Reserve extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
		'hoss_id' => 'required',
		'user_id' => 'required',
		'description' => 'required'
	);
}
