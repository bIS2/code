<?php
/*
* Represents the table State in the database, relationships, methods and attributes.
*/

class State extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
		// 'holding_id' => 'required',
		// 'user_id' => 'required',
		// 'state' => 'required'
	);

  public static function boot() {
    parent::boot();
		State::observe(new StateObserver);
  }

  public function holding() {
      return $this->belongsTo('Holding');
  }	
  public function user() {
      return $this->belongsTo('User');
  }  

  // SCOPES
  public function scopeInLibrary($query,$library_id=null){

    $library_id = ($library_id) ? $library_id : Auth::user()->library_id; 

    return $query->whereIn( 'holding_id', function($query) use ($library_id) { 
      $query->select('id')->from('holdings')->whereLibraryId( $library_id ); 
    });
    

    // return $query->orderBy('created_at', 'DESC')->whereIn( 'holding_id', function($query) use ($library_id) { 
    //   $query->select('id')->from('holdings')->whereLibraryId( $library_id ); 
    // });
    
  }


}
