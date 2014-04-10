<?php $librarianclass = ' '.substr($hol->sys2, 0, 4); ?>
<div class="modal-dialog{{ $librarianclass }}">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h3 class="modal-title"><?= $hol->f245a.' :: '.htmlspecialchars($hol->f245b,ENT_QUOTES); ?></h3>
    </div>
    <form id="recalled" method="put" action="{{ url('sets/new-ho-s') }}/{{ $holdingsset_id }}" data-remote="true">

      <div class="modal-body similarity-table-container">
          <?php if (count($res) > 0) { ?>
            <script type="text/javascript">
              $('.similarity-table').dataTable({
                "bFilter": false,
                "bPaginate": false  
              });
            </script>
            <table class="table table-striped table-condensed flexme similarity-table">
              <thead>
                <tr>    
                  <th>
                    <input id="select-all" class="select-all" name="select-all" type="checkbox" value="1" data-target="#recalling-holdings">
                  </th>        
                  <th>id</th>        
                  <th>sys</th>            
                  <th>245a :: 245b</th>        
                  <th>score</th>       
                  <th>flag</th>           
                </tr>
              </thead>
              <tbody id="recalling-holdings">
                <?php 
                  $i = 0;
                  $total = count($res);
                ?>
                @foreach ($res as $holding)
                  <?php if (in_array(holding::find($holding['id'])->holdingsset_id, $hospendingsid)) { ?>
                    <?php 
                      $librarianclass = ' '.substr($holding['sys2'], 0, 4);
                    ?>
                    <tr id="holding{{ $holding['id']; }}" class="{{ $librarianclass }}">     
                      <td>              
                        <input id="holding_id" name="holding_id[]" type="checkbox" value="{{ $holding['id'] }}" <?php if (!(in_array($holding['id'], $holdings))) { echo ' class="hl sel" '; } else { echo ' checked="checked" disabled ';  } ?> class="">
                      </td>
                      <td>{{ htmlspecialchars($holding['id'],ENT_QUOTES) }}</td>        
                      <td>{{ htmlspecialchars($holding['sys2'],ENT_QUOTES) }}</td>           
                      <td>{{ htmlspecialchars($holding['f245a'],ENT_QUOTES) }} :: {{ htmlspecialchars($holding['f245b'],ENT_QUOTES) }}</td>           
                      <td>{{ htmlspecialchars($holding['score'],ENT_QUOTES) }}</td>       
                      <td>{{ htmlspecialchars($holding['flag'],ENT_QUOTES) }}</td>       
                    </tr>
                    <?php } ?>
                @endforeach
              </tbody>
            </table>
          <?php } ?>
      </div>

      <div class="modal-footer">
        <input type="hidden" name="holdingsset_id" value="{{ $holdingsset_id }}">
        <input type="hidden" name="update_hos" value="1">
        <?php if (count($res) > 0) { ?>
          <input type="submit" name="submit" class="btn btn-primary" data-disable-with="..." value="{{ trans('general.add_to_HOS') }}">
        <?php } ?>
        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('general.close') }}</button>
      </div>
    </form>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->