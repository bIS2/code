<?php

class Holdingsset extends Eloquent {
	protected $guarded = array();
	public $timestamps = false;

	public static $rules = array();

  public static function boot() {
    parent::boot();
		Holdingsset::observe(new TraceObserver);
  }

  public function holdings() {
      return $this->hasMany('Holding');
  }

  public function confirm() {
    return $this->hasOne('Confirm');
  }

  public function groups() {
    return $this->belongsToMany('Group');
  }

  public function scopeOk($query){
    return $query
    ->whereIn('id', function($query) {
      $query -> select('holdingsset_id')->from('confirms');
    });
  }

  public function scopePendings($query){
    return $query
    ->whereNotIn('id', function($query) {
      $query -> select('holdingsset_id')->from('confirms');
    });
  }

  public function scopeAnnotated($query){
    return $query
    ->whereIn('id', Holding::annotated()->select('holdingsset_id')->lists('holdingsset_id'));
  }

  public function getIsannotatedAttribute(){
    return Holding::whereHoldingssetId($this->id)->annotated()->count() > 0;
  }
}
