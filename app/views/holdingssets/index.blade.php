@extends('layouts.default')

@section('toolbar')
	@include('holdingssets.toolbar')
@stop

{{-- Content --}}
@section('content')

<ul class="nav nav-tabs">
  <li <?php if (!isset($group_id)) { echo 'class="active"'; } ?>>
  	<a href="<?= route('sets.index')  ?>">
  		All <?= trans('holdingssets.title') ?>
  	</a>
  </li>
	<?php foreach ($groups as $group) { ?>
		<li id="group{{ $group->id }}" <?php if ($group_id == $group -> id) { echo 'class="active"'; } ?>>
			<a href="<?= route('sets.index',['group_id' => $group->id ])  ?>" class="pull-left"><?= $group->name  ?>
			</a>
			<?php if ($group_id != $group -> id) { ?>
			<!-- <a href="{{ action('HoldingssetsController@putDelGroup',[$group->id]) }}" class="btn btn-ok btn-xs" data-params="ok=true" data-remote="true" data-method="put" data-disable-with="..."><button aria-hidden="true" data-dismiss="modal" class="close pull-left" type="button">×</button></a> -->
			<a href="{{ action('HoldingssetsController@putDelGroup',[$group->id]) }}" class="close" data-params="ok=true" data-remote="true" data-method="put" data-disable-with="..."><i class="fa fa-times"></i></a>
			<?php } ?>
		</li>
	<?php } ?>
  <li>
	  <a href="#form-create-group" data-toggle="modal" class='link_bulk_action'>
	  	<i class="fa fa-folder-o"></i>
	  </a>
  </li>
</ul>
<div class="checkbox">
  <label>
    <input id="select-all" name="select-all" type="checkbox" value="1">
    {{ trans('holdingssets.select_all_hos') }}
  </label>
</div>
<section id="hosg" group_id = "<?php echo $group_id;  ?>">
	<!-- <ul class="list-group table"> -->
	<ul class="hol-sets table">
	@foreach ($holdingssets as $holdingsset)
		<?php $ok 	= ($holdingsset->ok) ? 'ok' : ''  ?>
		<?php $btn 	= ($holdingsset->ok) ? 'btn-success' : 'btn-default'  ?>
		<!-- <li class="panel list-group-item {{ $ok }}" id="<?= $holdingsset -> id; ?>"> -->
		<li id="<?= $holdingsset -> id; ?>">
			  <div class="panel-heading row">
		  		<input id="holdingsset_id" name="holdingsset_id[]" type="checkbox" value="<?= $holdingsset->id ?>" class="pull-left hl">
		      <div href="#<?= $holdingsset -> sys1; ?>" data-parent="#group-xx" title="<?= $holdingsset->f245a; ?>" data-toggle="collapse" class="accordion-toggle collapsed col-xs-10" opened="0">
		      	<?= $holdingsset->sys1.' :: '.htmlspecialchars(truncate($holdingsset->f245a, 100),ENT_QUOTES); ?>
		      	@if ($holdingsset->has('holdings') && $count1 = $holdingsset -> holdings -> count()) 
		      		<span class="badge"><i class="fa fa-files-o"></i> {{ $count1 }} </span>
		      	@endif
		      	@if ($holdingsset->has('groups') && ($count=$holdingsset->groups->count()>0)) 
		      		<span class="badge" title = "<?php 
		      			$currentgroups = $holdingsset->groups;
		      			$count = 0;
			      		foreach ($currentgroups as $currentgroup) {			
			      		$count++;      			
			      			if (($currentgroup['id']) == $group_id) echo strtoupper($currentgroup['name']).';';
			      			else
			      				echo strtolower($currentgroup['name']).';';
			      		} 
		      		?>"
		      		><i class="fa fa-folder-o"></i> {{ $count }}</span>
		      	@endif
		      </div>
		      <div class="text-right action-ok col-xs-1">
		      <!-- 	<a id="holdingsset<?= $holdingsset -> sys1; ?>add" class="btn btn-ok btn-xs {{ $btn }}" title="{{ trans('holdingssets.add_holdings') }}">
		      			<span class="glyphicon glyphicon-download-alt"></span>
		      	</a> -->
		      	@if (Auth::user()->hasRole('resuser')) 
		      	@else
		      	<a id="holdingsset<?= $holdingsset -> sys1; ?>" href="{{ action('HoldingssetsController@putOk',[$holdingsset->id]) }}" class="btn btn-ok btn-xs {{ $btn }}" data-params="ok=true" data-remote="true" data-method="put" data-disable-with="...">
		      			<span class="glyphicon glyphicon-thumbs-up"></span>
		      	</a>		
		      	@endif      	
		      </div>
			  </div>	
	  		<div class="panel-collapse collapse container" id="<?= $holdingsset -> sys1; ?>">
			    <div class="panel-body">
						<?php $k = 0; $k++; unset($valuesCounter); $valuesCounter = null; ?>
							@foreach ($holdingsset -> holdings as $holding)
								<?php 
									$valuesCounter = getValue('sys2',  $holding, $valuesCounter);
									$valuesCounter = getValue('f245a', $holding, $valuesCounter);
									$valuesCounter = getValue('f245b', $holding, $valuesCounter);
									$valuesCounter = getValue('f260a', $holding, $valuesCounter);
									$valuesCounter = getValue('f260b', $holding, $valuesCounter);
									$valuesCounter = getValue('f710a', $holding, $valuesCounter);
									$valuesCounter = getValue('f310a', $holding, $valuesCounter);
								?>
							@endforeach	
						<table class="table table-hover flexme table-bordered">
							<thead>
								<tr>
									<th>Actions</th>
									<th><?php echo '245a'; ?>
										<span class="glyphicon glyphicon-info-sign pop-over" data-html='true' data-content="<div>
											<?php 
											if (isset($valuesCounter['f245a'])) {
												foreach ($valuesCounter['f245a'] as $counter) {
												 	echo htmlentities($counter['title']).' -> '.$counter['count'].'<br>';
												} 
											}
											?>
										</div>" data-placement="bottom" data-toggle="popover" data-trigger="hover" type="button" data-original-title="" title="Column Sumary"></span>
									</th>
									<th><?php echo '245b'; ?>
										<span class="glyphicon glyphicon-info-sign pop-over" data-html='true' data-content="<div>
											<?php 
											if (isset($valuesCounter['f245b'])) {
												foreach ($valuesCounter['f245b'] as $counter) {
												 	echo htmlentities($counter['title']).' -> '.$counter['count'].'<br>';
												} 
											}
											?>
										</div>" data-placement="bottom" data-toggle="popover" data-trigger="hover" type="button" data-original-title="" title="Column Sumary"></span>
									</th>
									<!-- <th><?php echo '245c'; ?></th> -->
									<th class="hocrr_ptrn"><?php echo 'ocrr_ptrn'; ?></th>
									<!-- <th><?php echo '022a'; ?></th> -->
									<th><?php echo '260a'; ?>
										<span class="glyphicon glyphicon-info-sign pop-over" data-html='true' data-content="<div>
											<?php 
											if (isset($valuesCounter['f260a'])) {
												foreach ($valuesCounter['f260a'] as $counter) {
												 	echo htmlentities($counter['title']).' -> '.$counter['count'].'<br>';
												} 
											}
											?>
										</div>" data-placement="bottom" data-toggle="popover" data-trigger="hover" type="button" data-original-title="" title="Column Sumary"></span>
									</th>
									<th><?php echo '260b'; ?>
										<span class="glyphicon glyphicon-info-sign pop-over" data-html='true' data-content="<div>
											<?php
											if (isset($valuesCounter['f260b'])) {
												foreach ($valuesCounter['f260b'] as $counter) {
												 	echo $counter['title'].' -> '.$counter['count'].'<br>';
												} 
											}
											?>
										</div>" data-placement="bottom" data-toggle="popover" data-trigger="hover" type="button" data-original-title="" title="Column Sumary"></span>
									</th>
									<th><?php echo 'sys2'; ?>
										<span class="glyphicon glyphicon-info-sign pop-over" data-html='true' data-content="<div>
											<?php 
											if (isset($valuesCounter['sys2'])) {
												foreach ($valuesCounter['sys2'] as $counter) {
												 	echo htmlentities($counter['title']).' -> '.$counter['count'].'<br>';
												} 
											}
											?>
										</div>" data-placement="bottom" data-toggle="popover" data-trigger="hover" type="button" data-original-title="" title="Column Sumary"></span>
									</th>
									<th><?php echo '710a'; ?>
										<span class="glyphicon glyphicon-info-sign pop-over" data-html='true' data-content="<div>
											<?php 
											if (isset($valuesCounter['f710a'])) {
												foreach ($valuesCounter['f710a'] as $counter) {
												 	echo htmlentities($counter['title']).' -> '.$counter['count'].'<br>';
												} 
											}
											?>
										</div>" data-placement="bottom" data-toggle="popover" data-trigger="hover" type="button" data-original-title="" title="Column Sumary"></span>
									</th>
<!-- 									<th><?php echo '780t'; ?></th>
									<th><?php echo '362a'; ?></th>
									<th><?php echo '866a'; ?></th>
									<th><?php echo '866z'; ?></th> -->
									<th><?php echo '310a'; ?>
										<span class="glyphicon glyphicon-info-sign pop-over" data-html='true' data-content="<div>
											<?php 
											if (isset($valuesCounter['f310a'])) {
												foreach ($valuesCounter['f310a'] as $counter) {
												 	echo htmlentities($counter['title']).' -> '.$counter['count'].'<br>';
												} 
											}
											?>
										</div>" data-placement="bottom" data-toggle="popover" data-trigger="hover" type="button" data-original-title="" title="Column Sumary"></span>
									</th>
								</tr>
							</thead>
							<tbody>
							@foreach ($holdingsset -> holdings as $holding)
							<?php $btnlock 	= ($holding->locked) ? 'btn-warning ' : 'btn-default '; ?>	
							<?php $trclass 	= ($holding->locked) ? 'locked' : ''  ?>	
							<?php $ownertrclass 	= ($holding->is_owner == 't') ? ' is_owner' : '';  ?>	
							<?php $auxtrclass 	= ($holding->is_aux == 't') ? ' is_aux' : '';  ?>
							<?php $preftrclass 	= ($holding->is_pref == 't') ? ' is_pref' : '';  ?>	
							<?php $librarianclass = ' '.substr($holding->sys2, 0, 4);  ?>	
								<tr id="holding{{ $holding -> id; }}" class="{{ $trclass }}{{ $ownertrclass }}{{ $auxtrclass }}{{ $preftrclass }}{{ $librarianclass }}">
									<td>
						      	<a id="holding<?= $holding -> id; ?>lock" href="{{ action('HoldingssetsController@putLock',[$holding->id]) }}" class="btn-lock btn-xs {{ $btnlock }}" data-params="locked=true" data-remote="true" data-method="put" data-disable-with="..."><span class="glyphicon glyphicon-lock"></span></a>
		      					@if (Auth::user()->hasRole('resuser')) 
										@else
										<a href="<?= route('holdings.show', $holding->id) ?>" class="btn-xs btn-default" data-target="#modal-show" data-toggle="modal"><span class="glyphicon glyphicon-eye-open"></span></a>
										<a href="http://bis.trialog.ch/sets/from-library/<?= $holding->id; ?>" class="btn-xs btn-default" data-target="#modal-show" data-toggle="modal"><span class="glyphicon glyphicon-list-alt"></span></a>
						      @endif 
									</td>
									<td>
										<?php
											echo htmlspecialchars($holding->f245a); 
										?>
									</td>
									<td><?php echo htmlspecialchars($holding->f245b); ?></td>
									<!-- <td><?php echo $holding->f245c; ?></td> -->
									<td class="ocrr_ptrn">
										<?php											
											$ocrr_ptrn = str_split($holding->ocrr_ptrn);
											$j_ptrn = str_split($holding->j_ptrn);
											$aux_ptrn = str_split($holding->aux_ptrn);
											$i = 0;
											foreach ($ocrr_ptrn as $ocrr) { 
											 	switch ($ocrr) {
											 		case '0':
											 			echo '<i class="fa fa-square-o fa-lg"></i>';
											 			break;											 		
											 		case '1':
											 			$classj = '';
											 			$classaux = '';
											 		 	if (isset($j_ptrn[$i])) $classj = ($j_ptrn[$i] == '1') ? ' j' : ''; 
											 			if (isset($aux_ptrn[$i]))  $classaux = ($aux_ptrn[$i] == '1') ? ' aux' : ''; 
											 			echo '<i class="fa fa-square fa-lg'.$classj.$classaux.'"></i>';
											 			break;
											 	}
											 $i++; } 
										?>
									</td>
									<!-- <td><?php echo $holding->f022a; ?></td> -->
									<td><?php echo htmlspecialchars($holding->f260a); ?></td>
									<td><?php echo htmlspecialchars($holding->f260b); ?></td>
									<td><?php echo $holding->sys2; ?></td>
									<td><?php echo $holding->f710a; ?></td>

<!-- 									<td><?php echo $holding->f780t; ?></td>
									<td><?php echo $holding->f362a; ?></td>
									<td><?php echo $holding->f866a; ?></td>
									<td><?php echo $holding->f866z; ?></td> -->

									<td><?php echo $holding->f310a; ?></td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
		</li>
	@endforeach
	</ul>
</section>	

@include('groups.create')
<div class="remote">
<div id="modal-show" class="modal face"><div class="modal-body"></div></div>
</div>
<div class="remote">
<div id="modal-show-external" class="modal face"><div class="modal-body"></div></div>
</div>
@stop

<?php 

	$valuesCounter = null;

	function truncate($str, $length, $trailing = '...') {
    $length-=strlen($trailing);
    if (strlen($str) > $length) {
      $res = substr($str, 0, $length);
      $res .= $trailing;
    } else {
      $res = $str;
    }
    return $res;
	}

	function getValue($field, $holding, $valuesCounter) {

		if (!isset($valuesCounter[$field][htmlspecialchars($holding->$field)]) && (($holding->$field) != '')) { 
			$valuesCounter[$field][htmlspecialchars($holding->$field)]['title'] = htmlspecialchars($holding->$field); 
			$valuesCounter[$field][htmlspecialchars($holding->$field)]['count'] = 0; 
		} 
		if (($holding->$field) != '') {
			$temp = $valuesCounter[$field][htmlspecialchars($holding->$field)]['count']; 
			$temp++; 
			$valuesCounter[$field][htmlspecialchars($holding->$field)]['count'] = $temp; 
		}
		return $valuesCounter;
	}
?>