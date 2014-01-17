    
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">{{{ trans('tags.title-create') }}}</h4>
        </div>

        <div class="modal-body">

			<h1>All States</h1>

			<p>{{ link_to_route('states.create', 'Add new state') }}</p>

			@if ($states->count())
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>Date</th>
							<th>State</th>
							<th>User_id</th>
						</tr>
					</thead>

					<tbody>
						@foreach ($states as $state)
							<tr>
								<td>{{ $state->created_at }}</td>
								<td>{{{ $state->state }}}</td>
								<td>{{{ $state->user->username }}}</td>

							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				There are no states
			@endif


    	</div> <!-- /.modal-body -->
    <div class="modal-footer">
<!--
      <a href="#" class="btn btn-default" data-dismiss="modal" ><?= trans('general.close') ?></a>
      <button type="submit" class="btn btn-warning" ><?= trans('general.reset') ?></button>
      <button type="submit" class="btn btn-success" ><?= trans('general.save') ?></button>
-->
    </div>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->