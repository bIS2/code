<?php
/*
* Represents the table Holdings in the database, relationships, methods and attributes. *
*/
class Holding extends Eloquent {
	protected $guarded = array();
	public static $rules = array();
	public $timestamps = false;

  public static function boot() {
    parent::boot();
		Holding::observe(new HoldingObserver);
  }	

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
  
  public function hlists(){
    return $this->belongsToMany('Hlist');
  }
  
  public function ok(){
    return $this->hasOne('Ok');
  }

  public function comment(){
    return $this->hasOne('Comment');
  }

  public function receiveds(){
    return $this->hasMany('Received');
  }

  public function states(){
    return $this->hasMany('State');
  }

  // Scopes

  public function scopeDefaults($query){
  	return $query->with('notes', 'states', 'comment')->orderBy('f852j','f852c')->wasConfirmed()->inLibrary();
  }

  public function scopeInit ($query){
  	$query = $query->defaults();

    if ( Auth::user()->hasRole('postuser') ) 
      $query->revisedsCorrects();
    
    if ( Auth::user()->hasRole('magvuser') ) 
      // $query->confirms()->noReviseds()->ownerOrAux();
      $query->confirms()->ownerOrAux()->nodeliveries();

    if ( Auth::user()->hasRole('maguser') ) {
    	$lists = Hlist::whereWorkerId(Auth::user()->id)->lists('id');
    	$query
    			->join('hlist_holding', 'hlist_holding.holding_id','=', 'holdings.id')
    			->join('hlists', 'hlist_holding.hlist_id','=', 'hlists.id')
    			->whereIn('hlists.id',$lists);
    }
			// $query->whereIn('holdings.id', function($query){ $query->from('hlists')->holdings() } ) confirms()->ownerOrAux()->nodeliveries();

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
  	return $query->wasConfirmed();
  }

  public function scopeCorrects($query){
  	return $query->whereState('ok');
  	// return $query->whereIn( 'holdings.id', function($query){ $query->select('holding_id')->from('oks'); });
  }

  public function scopeDeliveries($query) {
  	return $query->whereState('delivery');
  }

  public function scopeNoDeliveries($query) {
  	return $query->where( 'state','<>','delivery');
  }

  public function scopeReceiveds($query) {
  	return $query->whereState('receive');
  }

  public function scopeNoReceiveds($query) {
  	return $query->where( 'state','<>','receive');
  }

  public function scopeReviseds($query){
  	return $query->where('state','like','revised_%');
  }


  public function scopeRevisedsCorrects($query){
    return $query->whereState('revised_ok');
  }

  public function scopeWasConfirmed($query) {
    return $query->whereIn( 'holdings.id', function($query){ $query->select('holding_id')->from('states')->whereState('confirmed'); })->where('state', '<>', 'blank')->where('state', '<>', 'revised_annotated')->where('state', '<>', 'incorrected');
  }

  public function scopeRevisedsAnnotated($query){
  	return $query->whereState('revised_annotated');
  }

  public function scopeNoReviseds($query){
  	// return $query->whereNotIn( 'holdings.id', function($query){ $query->select('holding_id')->from('reviseds'); });
  }

  public function scopeCommenteds($query){
    return $query->whereIn( 'holdings.id', function($query){ $query->select('holding_id')->from('comments'); });
  }

  public function scopeReserved($query){
    return $query->whereIn( 'holdings.id', function($query){ $query->select('holding_id')->from('lockeds'); });
  }
  
  public function scopeWithState( $query, $state ){
    return $query->defaults()->where('state','like',$state."%");
  }

  public function scopePendings($query){

  	return $query
	    ->whereState('confirmed');
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

  // Return the counter states in holding by library. Is used to plot stats 
  public function scopeCountState($query,$state=''){

		$result = $query->select(DB::raw('libraries.code as library, count(*) as count'))
							->join('states','holdings.id','=','states.holding_id')
							->join('libraries','holdings.library_id','=','libraries.id')
							->where('holdings.state','like',$state.'%')->orWhere('states.state','like',$state.'%')
							->groupBy('libraries.code');

		return $result;
  }

  // Attrubutes States
  public function getIsConfirmedAttribute(){
    return ( $this->state == 'confirmed' );
  }

  public function getIsCorrectAttribute(){
    return ( $this->state == 'ok' );
  }

  public function getIsAnnotatedAttribute(){
    $state =  $this->state;
    return (( $state == 'revised_annotated') || ($state == 'annotated'));
  }

  public function getIsRevisedAttribute(){
    return ( substr($this->state,0,8)=='revised_' );
  }

  public function getWasRevisedAttribute(){
    return $this->states()->where('state','like','revised_%')->exists();
  }

  public function getIsDeliveryAttribute(){
    return ( $this->state == 'delivery' );
  }

  public function getWasDeliveryAttribute(){
    return $this->states()->whereState('delivery')->exists();
  }

  public function getWasReceivedyAttribute(){
    return $this->states()->whereState('receive')->exists();
  }

  public function getIsReceivedAttribute(){
    return ( $this->state == 'received' );
  }

  public function getIsTrashedAttribute(){
    return ( $this->state == 'trash' );
  }

  public function getIsBurnedAttribute(){
    return ( $this->state == 'burn' );
  }

  public function getIsBlankAttribute(){
    return ( $this->state == 'blank' );
  }

  public function getIsStateAttribute($state){
    return ( $this->state == $state );
  }

  public function getTitleStateAttribute($state){
    return trans( 'states.'.$this->state);
  }

  // Attrubutes CSS Class

  public function getCssAttribute(){
  	return $this->class_owner.' '.$this->class_correct.' '.$this->class_aux.' '.$this->class_pref.' '.$this->class_revised.' '.$this->class_annotated.' '.$this->class_delivered.' '.$this->class_received;
  }

  public function getClassOwnerAttribute(){
    return (($this->is_owner == '1') || ($this->is_owner == 't')) ? ' is_owner' : '';
  }

  public function getClassPrefAttribute(){
    return (($this->is_pref == '1') || ($this->is_pref == 't')) ? ' is_pref' : '';
  }

  public function getClassAuxAttribute(){
    return (($this->is_aux == '1') || ($this->is_aux == 't')) ? ' is_aux' : '';
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

  public function getClassDeliveredAttribute(){
  	return ( $this->is_delivery && !Auth::user()->hasRole('speichuser') )   ? 'delivered' : '';
  }  

  public function getClassReceivedAttribute(){
  	return ( $this->is_received && Auth::user()->hasRole('speichuser') )   ? 'received' : '';
  }

  public function getPatrnAttribute($buttons){

    $ptrn = explode('|', $this->holdingsset->ptrn);
    $ocrr_ptrn = str_split($this->ocrr_ptrn);
    $j_ptrn = str_split($this->j_ptrn);
    $aux_ptrn = str_split($this->aux_ptrn);
    $i = 0;
    $ret = '<div style="display: inline-block;" data-toggle="buttons" class="'.$this->class_owner.'">';

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
    $ret = '<div style="display: inline-block;" class="'.$this->class_owner.'">';

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
        $ret .= '<i class="fa fa-square pop-over btn'.$classj.$classaux.'" data-content="<strong>'.$this->f852b.' | '.$this->f852h.' | '.$ptrn[$i].'</strong>" data-html="true" data-placement="top" data-toggle="popover" type="button" data-trigger="hover" data-original-title="" title=""></i>';
        break;
      }
      $i++; 
    }
    $ret .= "</div>";
    return $ret;

  }

  public function show($field, $len = 30) {
    
    if ($field == 'f866a') {
      if ($this->f866aupdated == '') { 
        $field = 'f866a';
      }
      else {
        $field = 'f866aupdated';
      }
    }

  	$str = $this->clean($this->$field);
    return (strlen($str) > $len) ? '<span class="pop-over" data-content="<strong>'.$str.'</strong>" data-placement="top" data-toggle="popover" data-html="true" type="button" data-trigger="hover">'.truncate($str, 30).'</span>' : $str;
  }

  public function clean($value){
    return htmlspecialchars($value);
  }


  public function bibuser_actions($holdingsset, $order) { 
    $holding = $this;
    $HOSincorrect = $holdingsset->is_incorrect;
    $btn  = 'btn-default';
    $HOSconfirm   = $holdingsset->confirm()->exists();
    $HOSannotated = $holdingsset->is_annotated;
    $btn  = ($HOSconfirm) ? 'btn-success disabled' : $btn;
    $btn  = ($holdingsset->is_unconfirmable) ? 'btn-success' : $btn;
    $btn  = ($HOSincorrect) ? 'btn-danger' : $btn;
    $btnlock  = ($holding->locked()->exists()) ? 'btn-warning ' : ''; 
    $ownertrclass = ($holding->is_owner == 't') ? ' is_owner' : '';
    $auxtrclass   = ($holding->is_aux == 't') ? ' is_aux' : ''; 
    if (isset($aux_ptrn[$i]))  $classaux = ($aux_ptrn[$i] == '1') ? ' aux' : ''; 
    $librarianclass = ' '.substr($holding->sys2, 0, 4); 
    $top = ($order == 1) ? 'right' : 'top';
    ?>
     <!--  
        <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown">
          <?= trans('general.action'); ?> <i class="fa  fa-caret-right"></i>
        </button>
        -->     
              <a href="<?= route('holdings.show', $holding->id); ?>" data-target="#modal-show" data-toggle="modal"><span class="fa fa-eye pop-over" data-content="<strong><?= trans('holdingssets.see_more_information'); ?></strong>" data-placement="<?= $top ?>" data-toggle="popover" data-html="true" data-trigger="hover"></span></a>

              <a href="/sets/from-library/<?= $holding->id; ?>" set="<?=$holdingsset->id; ?>" data-target="#modal-show" data-toggle="modal"><span class="fa fa-external-link pop-over" data-content="<strong><?= trans('holdingssets.see_information_from_original_system'); ?></strong>" data-placement="<?= $top ?>" data-toggle="popover" data-html="true" data-trigger="hover"></span></a>
              |
            <?php if (!($HOSconfirm) && !($HOSincorrect) && !($holding->locked)) : ?>
              
              <a id="holding<?=$holding -> id;; ?>delete" set="<?=$holdingsset->id; ?>"  href="<?= action('HoldingssetsController@putNewHOS',[$holding->id]); ?>" data-remote="true" data-method="put" data-params="holdingsset_id=<?=$holdingsset->id; ?>" data-disable-with="..." class="pop-over" data-content="<strong><?= trans('holdingssets.remove_from_HOS'); ?></strong>" data-placement="<?= $top ?>" data-toggle="popover" data-html="true" data-trigger="hover"><span class="fa fa-times"></span></a>
              |
              <a href="/sets/recall-holdings/<?= $holding->id; ?>" set="<?=$holdingsset->id; ?>" data-target="#modal-show" data-toggle="modal"><span class="fa fa-crosshairs pop-over" data-content="<strong><?= trans('holdingssets.recall_hos_from_this_holding'); ?></strong>" data-placement="<?= $top ?>" data-toggle="popover" data-html="true" data-trigger="hover"></span></a>


              <a href="/sets/similarity-search/<?= $holding->id; ?>" set="<?=$holdingsset->id; ?>" data-target="#modal-show" data-toggle="modal"><span class="fa fa-search pop-over" data-content="<strong><?= trans('holdingssets.similarity_search_from_this_holding'); ?></strong>" data-placement="<?= $top ?>" data-toggle="popover" data-html="true" data-trigger="hover"></span></a>
              |
            <?php if ($ownertrclass == '') : ?>

                <a id="holding<?=$holding -> id; ?>forceowner" set="<?=$holdingsset->id; ?>" href="<?= action('HoldingssetsController@putForceOwner',[$holding->id]); ?>" data-remote="true" data-method="put" data-params="holdingsset_id=<?=$holdingsset->id; ?>" data-disable-with="..."><span class="fa fa-stop text-danger pop-over" data-content="<strong><?= trans('holdingssets.force_owner'); ?></strong>" data-placement="<?= $top ?>" data-toggle="popover" data-html="true" data-trigger="hover"></span></a>

            <?php endif ?>

              <a id="holding<?=$holding -> id; ?>forceaux" set="<?=$holdingsset->id; ?>" href="<?= action('HoldingssetsController@putForceAux',[$holding->id]); ?>?unique_aux=1&holdingsset_id=<?= $holdingsset->id; ?>&ptrn=<?= $holding->aux_ptrn; ?>" data-remote="true" data-method="put" data-params="holdingsset_id=<?=$holdingsset->id; ?>" data-disable-with="..." class="forceaux"><span class="fa fa-stop text-warning pop-over" data-content="<strong><?= trans('holdingssets.force_aux'); ?></strong>" data-placement="<?= $top ?>" data-toggle="popover" data-html="true" data-trigger="hover"></span></a>

              <a id="holding<?=$holding -> id; ?>forceblue" set="<?=$holdingsset->id; ?>" href="<?= action('HoldingssetsController@putForceBlue',[$holding->id]); ?>" data-remote="true" data-method="put" data-params="holdingsset_id=<?=$holdingsset->id; ?>" data-disable-with="..." data-disable-with="..." class="forceblue"><span class="fa fa-stop text-primary pop-over" data-content="<strong><?= trans('holdingssets.force_blue'); ?></strong>" data-placement="<?= $top ?>" data-toggle="popover" data-html="true" data-trigger="hover"></span></a>           

          <?php endif ?>

          <?php if ($holding->is_annotated) : ?>
            |
              <a href="<?= route('notes.create',['holding_id'=>$holding->id, 'consult' => '1']); ?>" data-toggle="modal" data-target="#form-create-notes" class="btn-link btn-xs btn-tag">
                <span class="fa fa-tags text-danger pop-over" data-content="<strong><?= trans('holdingssets.see_storeman_annotations'); ?></strong>" data-placement="<?= $top ?>" data-toggle="popover" data-html="true" data-trigger="hover"></span>
              </a>
    

          <?php endif ?>

      <?php if ((Auth::user()->hasRole('resuser')) && (Auth::user()->library->id == $holding->library_id)) : ?>
        <?php if ($holding->locked()->exists()) : ?>         
            <a id="holding<?= $holding -> id; ?>lock" set="<?=$holdingsset->id; ?>" href="<?= route('lockeds.store',['holding_id' => $holding->id]); ?>" class="<?= $btnlock; ?>" data-remote="true" data-method="post" data-params="state=locked&holdingsset_id=<?=$holdingsset->id; ?>"  data-disable-with="..." ><span class="glyphicon glyphicon-lock pop-over" data-content="<strong><?= trans('holdingssets.reserved_by'); ?> </strong><?= $holding->locked->user->name; ?><br><strong><?= trans('holdingssets.on_behalf_of'); ?></strong> <?= $holding->locked->comments; ?>" data-placement="right" data-toggle="popover" data-html="true" data-trigger="hover"></span></a>
          <?php else : ?>          
            <a id="holding<?= $holding -> id; ?>lock" set="<?=$holdingsset->id; ?>" href="#" class="editable" data-type="text" data-pk="<?=$holdingsset->id; ?>" data-url="<?= route('lockeds.update',[$holding->id]); ?>"><span class="glyphicon glyphicon-lock pop-over" data-content="<strong><?php if ($btn != 'btn-success disabled') { echo trans('holdinssets.lock_hol'); } else { echo trans('holdingssets.unlock_hol'); ?></strong> <?= $holding->locked->comments; ?><?php } ?>" data-placement="right" data-toggle="popover" data-html="true" data-trigger="hover"></span></a>          
        <?php endif ?>
      <?php elseif($holding->locked) : ?>    
          <a id="holding<?= $holding -> id; ?>lock" class="<?= $btnlock; ?>"><span class="glyphicon glyphicon-lock pop-over" data-content="<strong><?= trans('holdingssets.reserved_by'); ?> </strong><?= $holding->locked->user->name; ?><br><strong><?= trans('holdingssets.on_behalf_of'); ?></strong> <?= $holding->locked->comments; ?>" data-placement="right" data-toggle="popover" data-html="true" data-trigger="hover"></span></a>
      <?php endif ?>
          <!-- </ul> -->
          <?php  ?>
      </div>
      <?php }
    }
