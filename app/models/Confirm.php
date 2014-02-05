<?php
/*
* Represents the table Confirm in the database, relationships, methods and attributes.
*
*/

class Confirm extends Eloquent {
	protected $guarded = array();

  public static function boot() {
    parent::boot();
    Confirm::observe(new ConfirmObserver);
  }

	public static $rules = array(
		'holdingsset_id' => 'required',
		'user_id' => 'required'
	);

	public function holdingsset() {
    return $this->belongsTo('Holdingsset');
  }

	public function user() {
    return $this->belongsTo('User');
  }
}
