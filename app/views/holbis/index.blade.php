@extends('admin.layouts.default')

{{-- Content --}}
@section('content')
<?php 
	$i = 0;
	$currentgroup = -1;
?>

<form method="" target="">
	<label for="group_name">Group name</label>
	<div class="input-group">
      <input type="text" name="name" id="group_name" class="form-control">
      <span class="input-group-btn">
        <button class="btn btn-default btn-primary" type="submit">Save</button>
      </span>
    </div>
</form>
<table id="hosg" class="table table-striped table-hover dataTabl">
	<thead>
		<tr>
			<th>
			  <h3 style="width: 240px; display: inline-block;">Sys1 </h3>
			  <h3 style="display: inline-block;">f245a</h3>
			</th>
		</tr>
	</thead>
	<tbody>
@foreach ($posts as $post)

<?php if ($currentgroup != $post->holgroups_id) : $i++;  ?>

	<?php if ($currentgroup != '-1') :  ?>
							</tbody>
						</table>
					</div>
				</div>
			</td>
		</tr>
	<?php endif; ?>


	<tr class="panel">
		<td>
		  <div class="panel-heading">
		      <h4 href="#a1c<?php echo $i; ?>" data-parent="#group-xx" data-toggle="collapse" class="accordion-toggle collapsed" style="width: 240px; display: inline-block;"><?php echo $hg[ $post->holgroups_id - 1]['sys1']; ?>
		      <h4 href="#a1c<?php echo $i; ?>" data-parent="#group-xx" data-toggle="collapse" class="accordion-toggle collapsed" style="display: inline-block;"><?php echo $hg[ $post->holgroups_id - 1]['f245a']; ?></h4>
		  </div>
  		<div class="panel-collapse collapse container" id="a1c<?php echo $i; ?>" style="height: 0px;">
		     <div class="panel-body">
					<table class="table table-striped table-hover flexme flexme<?php if ($i == 1) echo $i;  ?>">
						<thead>
							<tr>
								<th><?php echo 'ACTIONS'; ?></th>
								<th><?php echo 'f245b'; ?></th>
								<th><?php echo 'f245c'; ?></th>
								<th><?php echo 'ocrr_ptrn'; ?></th>
								<th><?php echo 'f022a'; ?></th>
								<th><?php echo 'f260a'; ?></th>
								<th><?php echo 'f260b'; ?></th>
								<th><?php echo 'f710a'; ?></th>
								<th><?php echo 'f780t'; ?></th>
								<th><?php echo 'f362a'; ?></th>
								<th><?php echo 'f866a'; ?></th>
								<th><?php echo 'f866z'; ?></th>
								<th><?php echo 'f310a'; ?></th>
							</tr>
						</thead>
						<tbody>
					<? $currentgroup = $post->holgroups_id; $k = 0; endif; $k++; ?>
						<tr>
							<td style="text-align: center; vertical-align: middle !important; ">
								<a href="#" style="color:green"> R </a>&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="#" style="color:red"> OK </a>&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="#" style="color:blue"> X </a>
							</td>
							<td><?php echo $post->f245b; ?></td>
							<td><?php echo $post->f245c; ?></td>
							<td><?php echo $post->ocrr_ptrn; ?></td>
							<td><?php echo $post->f022a; ?></td>
							<td><?php echo $post->f260a; ?></td>
							<td><?php echo $post->f260b; ?></td>
							<td><?php echo $post->f710a; ?></td>
							<td><?php echo $post->f780t; ?></td>
							<td><?php echo $post->f362a; ?></td>
							<td><?php echo $post->f866a; ?></td>
							<td><?php echo $post->f866z; ?></td>
							<td><?php echo $post->f310a; ?></td>
						</tr>
	@endforeach

							</tbody>
						</table>
					</div>
				</div>
			</td>
		</tr>

	</tbody>
</table>
@stop
{{-- Scripts --}}
@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {

			$('#hosg').dataTable({
		        "aLengthMenu": [[10, 50, 100, 250, -1], [10, 50, 100, 250, "All"]]
		      }
		    );
			
			// $('.flexme').dataTable({"bPaginate": false,"bFilter": false});

			// $('.flexme').flexigrid();

			// $('.hosg').flexigrid();
		});
	</script>
@stop
