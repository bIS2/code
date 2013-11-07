@extends('layouts.default')


{{-- Content --}}
@section('content')

<div class="row">
	<div class="col-lg-12">

			<nav role="navigation" class="navbar navbar-default navbar-static-top">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button data-target="#bs-example-navbar-collapse-8" data-toggle="collapse" class="navbar-toggle" type="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="#" class="navbar-brand">Brand</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div id="bs-example-navbar-collapse-8" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#" data-toggle="modal" data-target="#form-create-list" class='link_bulk_action'>{{ trans('holdings.create_list')}}</a></li>
            <li><a href="#" data-toggle="modal" data-target="#myModal">{{ trans('title.list')}}</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </nav>

	</div>
</div>	

<hr>


<div class="row">
	<div class="col-lg-12">

		<table id="holdings-items" class="table table-hover table-condensed ">
		<thead>
			<tr> 
				<th><input id="select-all" name="select-all" type="checkbox" value="1" /> </th>
				<th><?= '245a'; ?></th>
				<th><?= '245b'; ?></th>
				<th><?= '245c'; ?></th>
				<th><?= 'ocrr_ptrn'; ?></th>
				<th><?= '022a'; ?></th>
				<th><?= '260a'; ?></th>
				<th><?= '260b'; ?></th>
				<th><?= '710a'; ?></th>
				<th><?= '780t'; ?></th>
				<th><?= '362a'; ?></th>
				<td></td>
			</tr>
		</thead>
		<tbody>
		@foreach ($holdings as $holding)
			<tr id="<?= $holding->id ?>">
				<td><input id="holding_id" name="holding_id[]" type="checkbox" value="<?= $holding->id ?>" /></td>
<!-- 				<td>
					<a href="<?= route('holdings.show', $holding->id) ?>" data-target="#modal-show" data-toggle="modal" data-remote="<?= route('holdings.show', $holding->id) ?>">
						<span class="glyphicon glyphicon-eye-open"></span>
					</a>
					<a href="<?= route('holdings.show', $holding->id) ?>" data-target="#modal-show" data-toggle="modal" data-remote="<?= route('holdings.show', $holding->id) ?>">
											<span class="glyphicon glyphicon-ok"></span>
					</a>
				</td>

 -->		
 				<td>
 					{{ link_to_route('holdings.show', $holding->holdingsset->f245a,[ $holding->id ]) }}
 				</td>
				<td><?= $holding->f245b; ?></td>
				<td><?= $holding->f245c; ?></td>
				<td><?= $holding->ocrr_ptrn; ?></td>
				<td><?= $holding->f022a; ?></td>
				<td><?= $holding->f260a; ?></td>
				<td><?= $holding->f260b; ?></td>
				<td><?= $holding->f710a; ?></td>
				<td><?= $holding->f780t; ?></td>
				<td>
					  <a href="#" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-tag"></span></a>


					
				</td>

			</tr>
		@endforeach

		</tbody>
	</table>

	<?= $holdings->links()  ?>


	</div>
</div>

	@include('hlists.create')
	@include('hlists.index')
	<div id="modal-show" class="modal face"></div>
@stop
