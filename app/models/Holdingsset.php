<?php

class Holdingsset extends Eloquent {
	protected $guarded = array();
	public $timestamps = false;

	public static $rules = array();

  public static function boot() {
    parent::boot();
    Holdingsset::observe(new HoldingssetObserver);
  }

  public function holdings() {
      return $this->hasMany('Holding')->orderBy('is_owner','DESC')->orderBy('is_aux','DESC');
  }

  public function confirm() {
    return $this->hasOne('Confirm');
  }

  public function incorrect() {
    return $this->hasOne('Incorrect');
  }

  public function groups() {
    return $this->belongsToMany('Group');
  }


  // SCOPE
  // *********************************************************
  public function scopeOk($query){
    return $query
    ->whereState('ok');
  }

  public function scopeConfirmed($query){   
    return $query
    ->whereState('ok');
  }

  public function scopePendings($query){
    return $query
    ->whereState('blank');
  }  

  public function scopeIncorrects($query){
    return $query
    ->whereState('incorrect');
  }

  public function scopeCorrects($query){
    return $query
    ->whereState('ok');
  }

  public function scopeAnnotated($query){
    $ids = Holding::annotated()->select('holdingsset_id')->lists('holdingsset_id');
    if (count($ids) == 0 ) $ids = [-1];
    return $query
    ->whereIn('holdingssets.id', $ids);
  }

  public function scopeOwners($query){
    return $query->whereIn('id', function($query){ $query->select('holdingsset_id')->from('holdings')->whereIsOwner('t')->whereLibraryId( Auth::user()->library_id ); });
  }

  public function scopeAuxiliars($query){
    return $query->whereIn('id', function($query){ $query->select('holdingsset_id')->from('holdings')->whereIsAux('t')->whereLibraryId( Auth::user()->library_id ); });
  }

  public function scopeReceiveds($query) {

    $owners = $query->whereIn('id', function($query){ $query->select('holdingsset_id')->from('holdings')->whereIsOwner('t')->whereState('received')->whereLibraryId( Auth::user()->library_id ); })->lists('id'); 

    $auxs 	= $query->whereIn('id', function($query){ $query->select('holdingsset_id')->from('holdings')->whereIsAux('t')->whereState('received')->whereLibraryId( Auth::user()->library_id ); })->lists('id');

    $result = array_intersect($owners, $auxs);

    foreach ($owners as $owner) {
      $countauxs = count(Holding::whereHoldingssetId($owner)->whereIsAux('t')->lists('id'));
      $count_auxs_receiveds = ($countauxs > 0) ? count(Holding::whereHoldingssetId($this->id)->whereIsAux('t')->whereState('received')->lists('id')) : 0;
      if (!(in_array($owner, $result))) {
        if ($countauxs  == 0) $receiveds[] = $owner;
      }
      else {
        if ($count_auxs_receiveds == $countauxs['owner']) $receiveds[] = $owner;
      }
    }
    $receiveds = (count($receiveds) > 0) ? $receiveds : [-1];
    return holdingsset::whereIn('id', $receiveds);
  }


  // ATTRIBUTES
  //*************************************************************************
  public function getIsAnnotatedAttribute(){
    return Holding::whereHoldingssetId($this->id)->annotated()->count() > 0;
  }
  
  public function getIsCorrectAttribute(){
    return Holding::whereHoldingssetId($this->id)->corrects()->count() > 0;
  }

  public function getIsRevisedAttribute(){
    return Holding::whereHoldingssetId($this->id)->reviseds()->count() > 0;
  }

  public function getIsConfirmAttribute(){
    return $this->confirm()->exists();
  }

  public function getIsIncorrectAttribute(){
    return $this->incorrect()->exists();
  }

  public function getIsUnconfirmableAttribute(){  
    $ids = (count($this->holdings()->lists('id')) > 0) ? $this->holdings()->lists('id') : [-1];
    $inhlist = DB::table('hlist_holding')->whereIn('holding_id', $ids)->exists();
    return (($this->is_confirm) && (!($this->is_revised)) && (!($this->is_annotated)) && (!($this->is_correct)) && (!($inhlist)));
  }

  public function getShowlistgroupAttribute($query){
    $group_id = Input::get('group_id');
    $count = $this->groups->count();
    $ret = '';
    $i = 0;
    foreach ($this->groups as $currentgroup) {             
      if (($currentgroup['id']) == $group_id) $ret .= strtoupper($currentgroup['name']);  
      else
        $ret .= strtolower($currentgroup['name']);
      $i++;
      if ($i < $count) $ret .= ';';
    }
    return $ret;
  }
}
