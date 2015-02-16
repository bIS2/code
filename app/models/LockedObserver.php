<?php
/*
* Observer Lockeds (created, deleted)  Perform necessary actions after a specific event occurs on the model.
*
*/
class LockedObserver {

	public function created($model) {     
		// $user_id          = Auth::user()->id;
		$user_id          = $model->user_id;
		$id               = $model->holding->id;
		$current_state    = $model->holding->state;
		State::create( [ 'holding_id' => $id, 'user_id' => $user_id, 'state'=>$current_state ] );
	}

	public function deleted($model) {

	}
}