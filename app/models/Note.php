<?php
/*
* Represents the table Notes in the database, relationships, methods and attributes. Notes added to the holdings by maguser
*/

class Note extends Eloquent {
	protected $guarded = array();

  public static function boot() {
    parent::boot();
		Note::observe(new NoteObserver);
  }

  public function holding() {
      return $this->belongsTo('Holding');
  }

  public function user() {
      return $this->belongsTo('User');
  }
  
  public function tag() {
      return $this->belongsTo('Tag');
  }

  public function scopeInLibrary($query,$library_id=false){

    $library_id = ($library_id) ? $library_id : Auth::user()->library_id;
  	return $query->whereIn('holding_id', function($query) use ($library_id) { $query->select('id')->from('holdings')->whereLibraryId($library_id); });
  }


}
