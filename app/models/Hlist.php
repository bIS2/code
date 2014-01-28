<?php

class Hlist extends Eloquent {
	protected $guarded = array();

  public static function boot() {
    parent::boot();
		Hlist::observe(new HlistObserver);
		Hlist::observe(new TraceObserver);
  }


	public static $rules = array(
		// 'name' => 'required',
		// 'user_id' => 'required'
	);

	public function holdings(){
		return $this->belongsToMany('Holding');
	}
  
  public function user() {
  	return $this->belongsTo('User');
  }

  public function worker() {
  	return $this->belongsTo('User', 'worker_id');
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

  public function getIsFinishAttribute(){
    $total = $this->holdings()->count();
    $reviseds = $this->holdings()->whereState('ok')->orWhere('state', '=', 'annotated')->count();
    return ( ($total == $reviseds) && !$this->revised );
  }  
  
  public function getReadyToReviseAttribute(){
  	$this->is_finish;
  }

  public function getStateAttribute(){

  	$state = 'pending';

  	if (($this->type=='control') && $this->revised) 					$state = 'revised';
  	if ($this->type=='delivery' && $this->delivery->exists)  	$state = 'delivery';

  	return $state;
  	
  }

  public function getTypeIconAttribute(){

    $icon = '<i class="fa fa-tachometer"></i>';

    if ($this->type == 'delivery')  $icon = '<i class="fa fa-truck"></i>'; 
    elseif ($this->type == 'unsolve')  $icon = '<i class="fa fa-fire"></i>' ;

  	return $icon;
  }


  // SCOPES
  public function scopeInLibrary($query){
    return $query->whereIn( 'user_id', function($query){ $query->select('id')->from('users')->whereLibraryId( Auth::user()->library_id ); });
  }

  public function scopeDeliveries($query){
    return $query->whereIn( 'hlists.id', function($query){ $query->select('hlist_id')->from('deliveries'); });
  }

  public function scopeMy($query){

  	$query = $query->with('user','holdings');

    if ( Auth::user()->hasRole('maguser') || Auth::user()->hasRole('postuser') ) 
      $query = $query->whereWorkerId(Auth::user()->id);

    if ( Auth::user()->hasRole('speichuser') ) 
      $query = $query->deliveries();

    if (Auth::user()->hasRole('magvuser') && Auth::user()->hasRole('bibuser') )
	    $query = Auth::user()->hlists();

    return $query;
  }

}
