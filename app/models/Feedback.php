<?php

class Feedback extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
		'user_id' => 'required',
		'content' => 'required'
	);

  public function user() {
      return $this->belongsTo('User');
  }
}
