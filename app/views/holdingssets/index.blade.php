@extends('layouts.default')

@section('toolbar')
	@include('holdingssets.toolbar')
	@include('holdingssets.groupstabs')
@stop


{{-- Content --}}
@section('content')
<?php 
	$total = $holdingssets -> getTotal();
	$init = $holdingssets -> getTo();
?>
<section id="hosg" infinitepagination="1" group_id = "<?php echo $group_id;  ?>" @if ($init == $total) {{ 'class="nopaginate"' }} @endif >
	<ul id="hos-targets" class="hol-sets table list-group">
		<?php if (count($holdingssets) > 0) { ?>
			
		<?php }
		else { ?>
			<h2 class="text-info"><span class="fa fa-info-circle text-danger"></span> {{ trans('holdingssets.no_results_to_show') }}</h2>
			<?php } ?>
	</ul>
</section>	

@include('groups.create')
	<div class="remote">
		<div id="modal-show" class="modal face"><div class="modal-body"></div></div>
	</div>
	<div class="remote">
		<div id="modal-show-external" class="modal face"><div class="modal-body"></div></div>
	</div>
	<div class="remote">
 		<div id="form-create-notes" class="modal"></div>
	</div>
	<div class="remote">
 		<div id="set-show" class="modal face"></div>
	</div>
@stop