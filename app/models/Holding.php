<?php

class Holding extends Eloquent {
	protected $guarded = array();
	public static $rules = array();

  public function holdingsset() {
      return $this->belongsTo('Holdingsset');
  }
  
	public function cabinets(){
		return $this->belongsToMany('Cabinet');
	}

  public function scopeInLibrary(){
  	$id_user = Auth::user()->id;
  }

  public function scopeOutCabinet(){
  	
  }

}
