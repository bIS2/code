<?php

class Holding extends Eloquent {
	protected $guarded = array();
	public static $rules = array();
	public $timestamps = false;

  public static function boot() {
    parent::boot();
		//Holding::observe(new TraceObserver);
  }


  // Relations
  public function holdingsset() {
      return $this->belongsTo('Holdingsset');
  }
  
  public function notes() {
      return $this->hasMany('Note');
  }

  public function hlist(){
    return $this->belongsToMany('Hlist');
  }

	public function ok(){
		return $this->hasOne('Ok');
	}



  // Scopes
  public function scopeVerified ($query){
		return $query->whereIn('holdingsset_id',function($query){ $query->select('id')->from('holdingssets')->whereOk(true); });
  }

  public function scopeInLibrary(){
  	$id_user = Auth::user()->id;
  }

  public function scopeCorrects($query){
  	return $query->whereIn( 'holdings.id', function($query){ $query->select('holding_id')->from('oks'); } );
  }

  public function scopePendings($query){
  	return $query
  		->whereNotIn( 'id', function($query){ $query->select('holding_id')->from('oks'); } )
  		->whereNotIn( 'id', function($query){ $query->select('holding_id')->distinct()->from('notes'); } );
  }

  public function scopeAnnotated($query,$tag=''){
  	if ($tag==true)
	  	return $query->whereIn('holdings.id', function($query){ 
	      $query->select('holding_id')->from('Notes'); 
	    });
	  else
	  	return $query->whereIn('holdings.id', function($query){ 
	      $query->select('holding_id')->from('Notes')->whereTagId($tag); 
	    });
  }

  public function scopeOrphans($query){
    return $query->whereNotIn('id', function($query){ 
      $query->select('holding_id')->from('hlist_holding'); 
    });
  }

  public function scopeWorkers($query){
    return $query->whereNotIn('id', function($query){ 
      $query->select('holding_id')->from('hlist_holding'); 
    });
  }

  public function getIsCorrectAttribute(){
    return $this->ok()->exists();
  }

  public function getIsAnnotatedAttribute(){
    return $this->notes()->exists();
  }

  public function getPatrnAttribute(){
    $ocrr_ptrn = str_split($this->ocrr_ptrn);
    $j_ptrn = str_split($this->j_ptrn);
    $aux_ptrn = str_split($this->aux_ptrn);
    $i = 0;
    $ret = '';

    foreach ($ocrr_ptrn as $ocrr) { 
      switch ($ocrr) {
        case '0':
          $ret .= '<i class="fa fa-square-o fa-lg"></i>';
          break;                          
        case '1':
          $classj = '';
          $classaux = '';
          if (isset($j_ptrn[$i]))     $classj   = ($j_ptrn[$i] == '1') ? ' j' : ''; 
          if (isset($aux_ptrn[$i]))   $classaux = ($aux_ptrn[$i] == '1') ? ' aux' : ''; 
          $ret .= '<i class="fa fa-square fa-lg'.$classj.$classaux.'"></i>';
          break;
      }
     $i++; 
    } 
    return $ret;
  }


}
