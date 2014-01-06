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

	public function revised(){
		return $this->hasOne('Revised');
	}

	public function receiveds(){
		return $this->hasMany('Received');
	}

  // Scopes
  public function scopeInit ($query){

  	$query = $query->with('ok','notes')->orderBy('f852j','f852c')->inLibrary();

    if ( Auth::user()->hasRole('postuser') ) 
      $query->reviseds()->corrects();
    
    if ( Auth::user()->hasRole('magvuser') || Auth::user()->hasRole('maguser') ) 
      // $query->confirms()->noReviseds()->ownerOrAux();
      $query->confirms()->ownerOrAux();

    if ( Auth::user()->hasRole('speichuser') ) 
      $query->deliveries();

  	return $query;
  }

  public function scopeInLibrary($query){
  	return $query->whereLibraryId( Auth::user()->library_id );
  }

  public function scopeOwnerOrAux($query){
  	return $query->where( function($query){ $query->whereIsOwner('t')->orWhere('is_aux','=','t'); });
  }

  public function scopeConfirms($query){
  	return $query->whereIn( 'holdingsset_id', function($query){ $query->select('holdingsset_id')->from('confirms')->lists('holdingsset_id'); });
  }

  public function scopeCorrects($query){
  	return $query->whereIn( 'holdings.id', function($query){ $query->select('holding_id')->from('oks'); });
  }

  public function scopeDeliveries($query){
  	return $query->whereIn( 'holdings.id', function($query) { 
      $query->select('holding_id')->from('deliveries'); 
    });
  }

  public function scopeReviseds($query){
  	return $query->whereIn( 'holdings.id', function($query){ $query->select('holding_id')->from('reviseds'); });
  }

  public function scopeNoReviseds($query){
  	return $query->whereNotIn( 'holdings.id', function($query){ $query->select('holding_id')->from('reviseds'); });
  }

  public function scopePendings($query){
  	return $query
  		->whereNotIn( 'holdings.id', function($query){ $query->select('holding_id')->from('oks'); } )
  		->whereNotIn( 'holdings.id', function($query){ $query->select('holding_id')->distinct()->from('notes'); });
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
    return $query->whereNotIn('holdings.id', function($query){ 
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
    return $query->whereNotIn('holdings.id', function($query){ 
      $query->select('holding_id')->from('hlist_holding'); 
    });
  }
  
  // Attrubutes States
  public function getIsCorrectAttribute(){
    return $this->ok()->exists();
  }

  public function getIsAnnotatedAttribute(){
    return $this->notes()->exists();
  }

  public function getIsRevisedAttribute(){
    return $this->revised()->exists();
  }

  public function getIsDeliveryAttribute(){
    return $this->deliveries()->exists();
  }

  public function getIsReceivedAttribute(){
    return $this->receiveds()->exists();
  }



  // Attrubutes CSS Class

  public function getCssAttribute(){
  	return $this->class_owner.' '.$this->class_correct.' '.$this->class_revised.' '.$this->class_annotated;
  }

  public function getClassOwnerAttribute(){
    return ($this->is_owner == 't') ? ' is_owner' : '';
  }

  public function getClassCorrectAttribute(){
  	return ($this->is_correct) ? 'success' : '';
  }

  public function getClassAnnotatedAttribute(){
  	return ($this->is_annotated) ? 'danger' : '';
  }

  public function getClassRevisedAttribute(){
  	return ($this->is_revised) ? 'revised' : '';
  }

  public function getPatrnAttribute($buttons){

    $ptrn = explode('|', $this->holdingsset->ptrn);
    $ocrr_ptrn = str_split($this->ocrr_ptrn);
    $j_ptrn = str_split($this->j_ptrn);
    $aux_ptrn = str_split($this->aux_ptrn);
    $i = 0;
    $ret = '<div style="display: inline-block;" data-toggle="buttons">';

    foreach ($ocrr_ptrn as $ocrr) { 
      switch ($ocrr) {
        case '0':
          $ret .= '<i class="fa fa-square-o btn btn-xs btn-default "></i>';
          break;                          
        case '1':
          $classj = '';
          $classaux = '';
          if (isset($j_ptrn[$i]))     $classj   = ($j_ptrn[$i] == '1') ? ' j' : ''; 
          if (isset($aux_ptrn[$i]))   $classaux = ($aux_ptrn[$i] == '1') ? ' aux' : ''; 
          $pptrn1 = $ptrn[$i];
          // var_dump($pptrn1);
          $pptrn = explode('    ',$pptrn1);
          $ppptrn = $pptrn[0];
          if (count($pptrn) > 1) $ppptrn .= ' ('.$pptrn[1].')';
          $ret .= '<i class="fa fa-square pop-over btn btn-xs btn-default '.$classj.$classaux.'" data-content="<strong>'.$ppptrn.' | '.$this->f852b.' :: '.$this->f852h.'</strong>" data-html="true" data-placement="top" data-toggle="popover" class="btn btn-default" type="button" data-trigger="hover" data-original-title="" title=""></i>';
          break;
      }
     $i++; 
    }
    $ret .= "</div>";
    return $ret;

  }
  public function getPatrnNoBtnAttribute($buttons){

    $ptrn = explode('|', $this->holdingsset->ptrn);
    $ocrr_ptrn = str_split($this->ocrr_ptrn);
    $j_ptrn = str_split($this->j_ptrn);
    $aux_ptrn = str_split($this->aux_ptrn);
    $i = 0;
    $ret = '<div style="display: inline-block;">';

    foreach ($ocrr_ptrn as $ocrr) { 
      switch ($ocrr) {
        case '0':
          $ret .= '<i class="fa fa-square-o btn"></i>';
          break;                          
        case '1':
          $classj = '';
          $classaux = '';
          if (isset($j_ptrn[$i]))     $classj   = ($j_ptrn[$i] == '1') ? ' j' : ''; 
          if (isset($aux_ptrn[$i]))   $classaux = ($aux_ptrn[$i] == '1') ? ' aux' : ''; 
          $ret .= '<i class="fa fa-square pop-over btn'.$classj.$classaux.'" data-content="<strong>'.$this->f852b.' | '.$this->f852h.' | '.$ptrn[$i].'</strong>" data-html="true" data-placement="top" data-toggle="popover" class="btn btn-default" type="button" data-trigger="hover" data-original-title="" title=""></i>';
          break;
      }
     $i++; 
    }
    $ret .= "</div>";
    return $ret;

  }

}
