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

  public function scopeFindByHoldings($query,$field,$format){

  	$holdings= Holding::all();

		if ( $field=='f852b' )  $holdings = $holdings->whereRaw( sprintf( $format, 'LOWER('.$field.')', strtolower( $field ) ) );
		if ( $field=='f852h' ) 	$holdings = $holdings->whereRaw( sprintf( $format, 'LOWER('.$field.')', strtolower( $field ) ) );
		if ( $field=='f245a' )  $holdings = $holdings->whereRaw( sprintf( $format, 'LOWER('.$field.')', strtolower( $field ) ) );
		if ( $field=='f362a' ) 	$holdings = $holdings->whereRaw( sprintf( $format, 'LOWER('.$field.')', strtolower( $field ) ) );
		if ( $field=='f866a' ) 	$holdings = $holdings->whereRaw( sprintf( $format, 'LOWER('.$field.')', strtolower( $field ) ) );
		if ( $field=='f866z' ) 	$holdings = $holdings->whereRaw( sprintf( $format, 'LOWER('.$field.')', strtolower( $field ) ) );

    return $query->whereIn('id',$holdings->lists('holdinsset_id'));
  }

}
