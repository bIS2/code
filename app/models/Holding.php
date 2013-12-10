<?php

class Holding extends Eloquent {
	protected $guarded = array();
	public static $rules = array();
	public $timestamps = false;

  // Relations
  public function holdingsset() {
      return $this->belongsTo('Holdingsset');
  }
  
  public function library() {
      return $this->belongsTo('Library');
  }
  
  public function notes() {
      return $this->hasMany('Note');
  }

  public function locked() {
    return $this->hasOne('Locked');
  }
  
  public function hlist(){
    return $this->belongsToMany('Hlist');
  }

	public function ok(){
		return $this->hasOne('Ok');
	}

	public function delivery(){
		return $this->hasOne('Delivery');
	}

  // Scopes
  public function scopeVerified ($query){
		return $query
			->with('ok','notes')
			// Select Holdings Set confirmed by librarian
      ->whereIn( 'holdingsset_id', function($query){ 
      	$query->select('holdingsset_id')->from('confirms')->lists('holdingsset_id'); 
      })
			// and belongs to library of user
      ->where( function($query){ 
      	$query->whereF852b( Auth::user()->library->code )
      				->orWhereIn( 'f852b', explode(',',Auth::user()->library->sublibraries) ); 
      })
      // and with owner = true or auxiliar = true
      ->where(function($query){
        $query->whereIsOwner('t')->orWhere('is_aux','=','t');
      });
  }

  public function scopeCorrects($query){
  	return $query->whereIn( 'holdings.id', function($query){ $query->select('holding_id')->from('oks'); } );
  }

  public function scopeDeliveries($query){
  	return $query->whereIn( 'holdings.id', function($query){ $query->select('holding_id')->from('deliveries'); } );
  }

  public function scopePendings($query){
  	return $query
  		->whereNotIn( 'id', function($query){ $query->select('holding_id')->from('oks'); } )
  		->whereNotIn( 'id', function($query){ $query->select('holding_id')->distinct()->from('notes'); } );
  }

  public function scopeAnnotated($query,$tag_id='%'){

    if ($tag_id=='%') 
      $tag_ids = DB::table('notes')->lists('holding_id') ;
    else
      $tag_ids = DB::table('notes')->whereTagId($tag_id)->lists('holding_id');
   
      $tag_ids = (count($tag_ids) > 0) ? $tag_ids : [-1];
      return $query->whereIn('holdings.id', $tag_ids);
  } 

  public function scopeOrphans($query){
    return $query->whereNotIn('id', function($query){ 
      $query->select('holding_id')->from('hlist_holding'); 
    });
  }

  public function scopeOwner($query){
    return $query->whereIsOwner('t');
  }

  public function scopeAux($query){
    return $query->whereIsAux('t');
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

    $ptrn = explode('|', $this->holdingsset->ptrn);
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
          $ret .= '<i class="fa fa-square fa-lg pop-over'.$classj.$classaux.'" data-content="'.$this->f852b.' | '.$this->f852h.' | '.$ptrn[$i].'" data-placement="top" data-toggle="popover" class="btn btn-default" type="button" data-trigger="hover" data-original-title="" title=""></i>';
          break;
      }
     $i++; 
    } 
    return $ret;

  }

}
