<?php

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

  public function scopeInLibrary($query){
  	return $query->whereIn('holding_id', function($query){ $query->select('id')->from('holdings')->whereLibraryId(Auth::user()->id); });
  }


}
