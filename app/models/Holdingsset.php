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

    public function groups() {
        return $this->belongsToMany('Group');
    }

  public function scopeOk($query){
    return $query->whereOk(true);
  }

  public function scopePendings($query){
    return $query->whereOk(false);
  }

}
