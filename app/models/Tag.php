<?php
/*
* Represents the table Tags in the database, relationships, methods and attributes. Tag represents the types of notes that can be created on a holding
*/
class Tag extends Eloquent {
	protected $guarded = array();
	public $timestamps = false;

	public static $rules = array(
		'name' => 'required'
	);

  public function notes() {
      return $this->hasMany('Note');
  }

}
