<?php

class Note extends Eloquent {
	protected $guarded = array();

  public function holding() {
      return $this->belongsTo('Holding');
  }
  
  public function tag() {
      return $this->belongsTo('Tag');
  }

}
