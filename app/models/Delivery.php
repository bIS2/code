<?php

class Delivery extends Eloquent {
	protected $guarded = array();

  public static function boot() {
    parent::boot();
    Delivery::observe(new SetUserObserver);
    Delivery::observe(new DeliveryObserver);
  }

	public static $rules = array(
		'holding_id' => 'required'
	);

  public function holding() {
      return $this->belongsTo('Holding');
  }

  public function user() {
      return $this->belongsTo('user');
  }

  public function scopeInLibrary($query){
  	return $query->whereIn('holding_id', function($query){ $query->select('id')->from('holdings')->whereLibraryId(Auth::user()->id); });
  }
}
