  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
				<table class="table table-striped table-condensed">
					<tr>
					  <td>f245a</td>
						<td><?= $holding->f245a; ?></td>
					</tr>					
					<tr>
					  <td>f245b</td>
						<td><?= $holding->f245b; ?></td>
					</tr>
					<tr>
					  <td>f245c</td>
						<td><?= $holding->f245c; ?></td>
					</tr>
					<tr>
					  <td>ocrr_ptrn</td>
						<td><?= $holding->ocrr_ptrn; ?></td>
					</tr>
					<tr>
					  <td>f022a</td>
						<td><?= $holding->f022a; ?></td>
					</tr>
					<tr>
					  <td>f260a</td>
						<td><?= $holding->f260a; ?></td>
					</tr>
					<tr>
					  <td>f260b</td>
						<td><?= $holding->f260b; ?></td>
					</tr>
					<tr>
					  <td>f710a</td>
						<td><?= $holding->f710a; ?></td>
					</tr>
					<tr>
					  <td>f780t</td>
						<td><?= $holding->f780t; ?></td>
					</tr>
					<tr>
					  <td>f362a</td>
						<td><?= $holding->f362a; ?></td>
					</tr>
					<tr>
					  <td>f866a</td>
						<td><?= $holding->f866a; ?></td>
					</tr>
					<tr>
					  <td>f866z</td>
						<td><?= $holding->f866z; ?></td>
					</tr>
					<tr>
					  <td>f310a</td>
						<td><?= $holding->f310a; ?></td>
					</tr>
					<tr>
					  <td>f852b</td>
						<td><?= $holding->f852b; ?></td>
					</tr>
					<tr>
					  <td>f852h</td>
						<td><?= $holding->f852h; ?></td>
					</tr>
				</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= trans('general.prev') ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= trans('general.next') ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->


