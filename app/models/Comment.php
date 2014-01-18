<?php

class Comment extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
/*		'user_id' => 'required',
		'holding_id' => 'required',
		'content' => 'required'
*/	);

  public static function boot() {
    parent::boot();
		Comment::observe(new CommentObserver);
  }

  public function holding() {
      return $this->belongsTo('Holding');
  }

  public function user() {
      return $this->belongsTo('User');
  }

}
