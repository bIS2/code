<?php
/*
* Represents the table Confirm in the database, relationships, methods and attributes.
*
*/

class Locked extends Eloquent {
	protected $guarded = array();

  public static function boot() {
    parent::boot();
    Locked::observe(new LockedObserver);
  }

	public static $rules = array(
		'holding_id' => 'required',
		'user_id' => 'required'
	);

  public function holding() {
      return $this->belongsTo('Holding');
  }

  public function user() {
      return $this->belongsTo('User');
  }
}
