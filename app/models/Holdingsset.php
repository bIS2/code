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
      return $this->hasMany('Holding')->orderBy('is_owner','DESC')->orderBy('is_aux','DESC')->orderBy('weight','DESC');
  }

  public function confirm() {
    return $this->hasOne('Confirm');
  }

  public function groups() {
    return $this->belongsToMany('Group');
  }


  // SCOPE
  // *********************************************************
  public function scopeOk($query){
    return $query
    ->whereIn('holdingssets.id', function($query) {
      $query -> select('holdingsset_id')->from('confirms');
    });
  }

  public function scopeConfirmed($query){
    return $query->whereIn('holdingssets.id', function($query) { $query->select('holdingsset_id')->from('confirms'); });
  }

  public function scopePendings($query){
    return $query
    ->whereNotIn('holdingssets.id', function($query) {
      $query -> select('holdingsset_id')->from('confirms');
    });
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

  public function getIsUnconfirmableAttribute(){  
    $inhlist = DB::table('hlist_holding')->whereIn('holding_id', $this->holdings()->lists('id'))->exists();
    return (($this->is_confirm) && (!($this->is_revised)) && (!($this->is_correct)) && (!($inhlist)));
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
