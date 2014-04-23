<?php
/*
* Represents the table Incorrects in the database, relationships, methods and attributes. Represents holdings are inaccurate and can not be solved
*/

class Incorrect extends Eloquent {
	protected $guarded = array();
  
  public static function boot() {
    parent::boot();
    Incorrect::observe(new IncorrectObserver);
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
