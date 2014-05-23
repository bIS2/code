<?php
/*
* Represents the table Library in the database, relationships, methods and attributes. *
*/

class Library extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

  public function users() {  return $this->hasMany('User');  }

  public function holdings() {  return $this->hasMany('Holding');  }

  public function scopeLibraryperholding($query, $code) {
  	return $this->whereCode($code)->paginate(1);
  }	

  public function getHoldingsRevisedAttribute(){
  	return ($this->holdings_revised_annotated + $this->holdings_revised_ok);
  }
}
