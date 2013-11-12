<?php

class Holding extends Eloquent {
	protected $guarded = array();
	public static $rules = array();
	public $timestamps = false;

  public static function boot() {
    parent::boot();
		Holding::observe(new TraceObserver);
  }

  public function holdingsset() {
      return $this->belongsTo('Holdingsset');
  }
  
  public function tags() {
      return $this->belongsToMany('Tag')->withPivot('content');
  }

	public function hlist(){
		return $this->belongsToMany('Hlist');
	}

  public function scopeInLibrary(){
  	$id_user = Auth::user()->id;
  }

  public function scopeCorrects($query){
  	return $query->whereOk2(true);
  }

  public function scopePendings($query){
  	return $query->whereOk2(null)->whereNotIn('id', function($query){ 
      $query->select('holding_id')->from('holding_tag'); 
    });
  }

  public function scopeTagged($query){
  	return $query->whereIn('id', function($query){ 
      $query->select('holding_id')->from('holding_tag'); 
    });
  }

  public function scopeOrphans($query){
    return $query->whereNotIn('id', function($query){ 
      $query->select('holding_id')->from('hlist_holding'); 
    });
  }

}
