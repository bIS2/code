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
    ->whereIn('holdingssets.id', function($query) {
      $query -> select('holdingsset_id')->from('confirms');
    });
  }

  public function scopePendings($query){
    return $query
    ->whereNotIn('holdingssets.id', function($query) {
      $query -> select('holdingsset_id')->from('confirms');
    });
  }

  public function scopeAnnotated($query){
    $ids = Holding::annotated()->select('holdingsset_id')->lists('holdingsset_id');
    if (count($ids) == 0 ) $ids = [-1];
    return $query
    ->whereIn('holdingssets.id', $ids);
  }

  public function getIsannotatedAttribute(){
    return Holding::whereHoldingssetId($this->id)->annotated()->count() > 0;
  }

  public function getShowlistgroupAttribute($query){
    $group_id = Input::get('group_id');
    $count = $this->groups->count();
    $ret = '';
    $i = 0;
    foreach ($this->groups as $currentgroup) {             
      if (($currentgroup['id']) == $group_id) $ret .= strtoupper($currentgroup['name']);  
      else
        $ret .= strtolower($currentgroup['name']);
      $i++;
      if ($i < $count) $ret .= ';';
    }
    return $ret;
  }
}
