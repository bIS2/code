<?php

class Holding extends Eloquent {
	protected $guarded = array();
	public static $rules = array();
	public $timestamps = false;

  public function holdingsset() {
      return $this->belongsTo('Holdingsset');
  }
  
  public function tag() {
      return $this->belongsToMany('Tag')->withPivot('content');
  }

	public function hlist(){
		return $this->belongsToMany('Hlist');
	}

  public function scopeInLibrary(){
  	$id_user = Auth::user()->id;
  }

  public function scopeOutCabinet(){
  	
  }

}
