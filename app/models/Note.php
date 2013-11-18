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
  
  public function tag() {
      return $this->belongsTo('Tag');
  }

}
