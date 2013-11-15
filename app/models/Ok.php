<?php
// Marca los holding como ok
class Ok extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	// Relations
  public function holdings() {
      return $this->belongsTo('Holding');
  }

  public function user() {
      return $this->belongsTo('User');
  }

}
