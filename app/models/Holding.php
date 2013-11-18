<?php

class Holding extends Eloquent {
	protected $guarded = array();
	public static $rules = array();
	public $timestamps = false;

  public static function boot() {
    parent::boot();
		Holding::observe(new TraceObserver);
  }


  // Relations
  public function holdingsset() {
      return $this->belongsTo('Holdingsset');
  }
  
  public function notes() {
      return $this->hasMany('Note');
  }

  public function hlist(){
    return $this->belongsToMany('Hlist');
  }

	public function ok(){
		return $this->hasOne('Ok');
	}



  // Scopes
  public function scopeVerified ($query){
		return $query->whereIn('holdingsset_id',function($query){ $query->select('id')->from('holdingssets')->whereOk(true); });
  }

  public function scopeInLibrary(){
  	$id_user = Auth::user()->id;
  }

  public function scopeCorrects($query){
  	return $query->whereIn( 'holdings.id', function($query){ $query->select('holding_id')->from('oks'); } );
  }

  public function scopePendings($query){
  	return $query
  		->whereNotIn( 'id', function($query){ $query->select('holding_id')->from('oks'); } )
  		->whereNotIn( 'id', function($query){ $query->select('holding_id')->distinct()->from('notes'); } );
  }

  public function scopeAnnotated($query,$tag=''){
  	if ($tag)
	  	return $query->whereIn('holdings.id', function($query){ 
	      $query->select('holding_id')->from('Notes'); 
	    });
	  else
	  	return $query->whereIn('holdings.id', function($query){ 
	      $query->select('holding_id')->from('Notes')->whereTagId($tag); 
	    });
  }

  public function scopeOrphans($query){
    return $query->whereNotIn('id', function($query){ 
      $query->select('holding_id')->from('hlist_holding'); 
    });
  }

  public function scopeWorkers($query){
    return $query->whereNotIn('id', function($query){ 
      $query->select('holding_id')->from('hlist_holding'); 
    });
  }

  public function getIsCorrectAttribute(){
    return $this->ok()->exists();
  }

  public function getIsAnnotatedAttribute(){
    return $this->notes()->exists();
  }


}
