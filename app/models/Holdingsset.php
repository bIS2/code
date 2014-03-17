<?php
/*
* Represents the table Holdinssets (HOS) in the database, relationships, methods and attributes. *
*/

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

  public function scopeReserveds($query) {     
    $ids = Holding::reserved()->select('holdingsset_id')->lists('holdingsset_id');
    if (count($ids) == 0 ) $ids = [-1];
    return $query
    ->whereIn('holdingssets.id', $ids);
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
    return $query
    ->whereState('integrated');
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
    return ($this ->locked == 1);
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

  public function show($field, $len = 30) {
    $str = $this->clean($this->$field);
    return (strlen($str) > $len) ? '<span class="pop-over" data-content="<strong>'.$str.'</strong>" data-placement="top" data-toggle="popover" data-html="true" type="button" data-trigger="hover">'.truncate($str, $len).'</span>' : $str;
  }

  public function clean($value){
    return htmlspecialchars(stripslashes($value));
  }
}