<?php

class Hlist extends Eloquent {
	protected $guarded = array();

  public static function boot() {
    parent::boot();
		Hlist::observe(new TraceObserver);
  }


	public static $rules = array(
		'name' => 'required',
		'user_id' => 'required'
	);

	public function holdings(){
		return $this->belongsToMany('Holding');
	}
  
  public function user() {
  	return $this->belongsTo('User');
  }

  public function assigned() {
  	return $this->belongsTo('User');
  }

  public function delivery() {
  	return $this->hasOne('Delivery');
  }


  // ATTRIBUTES
  public function getIsDeliveryAttribute(){
    return $this->delivery()->exists();
  }


  // SCOPES
  public function scopeInLibrary($query){
    return $query->whereIn( 'user_id', function($query){ $query->select('id')->from('users')->whereLibraryId( Auth::user()->library_id ); });
  }

  public function scopeDeliveries($query){
    return $query->whereIn( 'hlists.id', function($query){ $query->select('hlist_id')->from('deliveries'); });
  }

  public function scopeMy($query){

    if ( Auth::user()->hasRole('speichuser') ) 
      $query = $query->inLibrary()->deliveries();
    else 
      $query = Auth::user()->hlists();
    

    return $query;
  }

}
