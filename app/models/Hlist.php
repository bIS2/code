<?php
/*
* Represents the table Hlists in the database, relationships, methods and attributes. Represents a list of holdings
*
*/
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
  	$finish = false;
  	if ($this->type=='control') {
	    $total 		= $this->holdings()->count();
	    $reviseds = $this->holdings_was_reviseds;
	    $finish =  ( ($total == $reviseds) && !$this->revised );
  	}

  	return $finish;
  }  
  
  public function getReadyToReviseAttribute(){
  	return $this->is_finish;
  }  

  public function getIsReceivedAttribute(){
  	return ( !$this->is_delivery ) ? false : $this->delivery->received;
  	
  }

  public function check_received(){

  	$received = false;
  	if ( $this->type=='delivery' && !$this->delivery->received ) {

	    $total 		= $this->holdings()->count();
	    $receiveds = $this->holdings()->where( function($query) { 
	    	$query->whereState('holdings.received')->orWhere('holdings.state','=','commented'); 
	    })->count();

	   	if ( $total == $receiveds ) $this->delivery->update(['received'=>1]);
	   	$received = $this->delivery->received;

  	}

    return $received;
  }  


  public function getHoldingsRevisedsAttribute(){
    return $this->holdings()->where( function($query) { 
      $query->whereState('holdings.annotated')->orWhere('holdings.state','=','ok'); 
    })->count();
  }

  //return the counter of holdings in list was annotated or ok state
  public function getHoldingsWasRevisedsAttribute(){
    return $this->holdings()->whereIn('holdings.id', function($query) { 
    	$query->select('holding_id')->from('states')->whereState('states.annotated')->orWhere('states.state','=','ok'); 
    })->count();
  }

  public function getHoldingsReceivedsAttribute(){
    return $this->holdings()->where( function($query) { 
    	$query->whereState('holdings.received')->orWhere('holdings.state','=','commented'); 
    })->count();
  }


  public function getStateAttribute($value){

  	return (($this->type=='control') && $this->revised )? 'revised' : $value;
  	
  }

  public function getTypeIconAttribute(){

    $icon = '<i class="fa fa-tachometer"></i>';

    if ($this->type == 'delivery')  $icon = '<i class="fa fa-truck"></i>'; 
    elseif ($this->type == 'unsolve')  $icon = '<i class="fa fa-fire"></i>' ;

  	return $icon;
  }

  // SCOPES
  public function scopeInLibrary($query){
  	$library_id = Auth::user()->library_id; 
    return $query->whereIn( 'user_id', function($query) use ($library_id) { 
    	$query->select('id')->from('users')->whereLibraryId( $library_id ); 
    });
  }

  public function scopeDeliveries($query){
     return $query->whereIn( 'hlists.id', function($query) { 
    	$query->select('hlist_id')->from('deliveries')->whereReceived(false);
    });

  }

  public function scopeMy($query){

    $query1=$query2=$query3=$query4=[];

  	$query = $query->with('user','holdings')->orderBy('created_at', 'desc');

    if ( Auth::user()->hasRole('maguser') || Auth::user()->hasRole('postuser') ) 
      $query = $query->whereIn('revised',[false,0,'f'])->whereWorkerId(Auth::user()->id);

    if ( Auth::user()->hasRole('speichuser') ) 
      $query = $query->deliveries();

    if ( Auth::user()->hasRole('bibuser') ) 
      $query = $query->whereUserId(Auth::user()->id);

    if (Auth::user()->hasRole('magvuser') || Auth::user()->hasRole('bibuser') )
	    $query = $query->inLibrary();

    return $query;
  }

}
