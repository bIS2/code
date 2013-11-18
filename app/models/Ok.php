<?php
// Marca los holding como ok
class Ok extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

  public static function boot() {
    parent::boot();
		Ok::observe(new OkObserver);
  }

	// Relations
  public function holdings() {
      return $this->belongsTo('Holding');
  }

  public function user() {
      return $this->belongsTo('User');
  }

}
