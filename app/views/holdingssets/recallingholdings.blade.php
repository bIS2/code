<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    </div>
    <form id="recalled" method="put" action="{{ url('sets/new-ho-s') }}/{{ $holdingsset_id }}" data-remote="true">
    <div class="modal-body">
        <table class="table table-bordered table-striped flexme">
          <thead>
            <tr>    
              <th>
                <input id="select-all" class="select-all" name="select-all" type="checkbox" value="1" data-target="#recalling-holdings">
              </th>        
              <th>id</th>        
              <th>sys</th>        
              <th>ptr</th>     
              <th>245a :: 245b</th>       
              <th>hbib</th>       
              <th>866a</th>       
              <th>852b</th>       
              <th>852h</th>       
            </tr>
          </thead>
          <tbody id="recalling-holdings">
            @foreach ($holdings as $holding)
              <?php 
                // $btnlock  = ($holding->locked()->exists()) ? 'btn-warning ' : ''; 
                // $trclass  = ($holding->locked()->exists()) ? 'locked' : '';
                // $ownertrclass = ($holding->is_owner == 't') ? ' is_owner' : '';
                // $auxtrclass   = ($holding->is_aux == 't') ? ' is_aux' : ''; 
                // $librarianclass = ' '.substr($holding->sys2, 0, 4);
              ?>
              <tr id="holding{{ $holding -> id; }}" class="{{ $trclass }}{{ $ownertrclass }}{{ $auxtrclass }}{{ $preftrclass }}{{ $librarianclass }}{{ ($holding->is_annotated) ? ' text-warning' : '' }}">     
                <td>              
                  <input id="holding_id" name="holding_id[]" type="checkbox" value="{{ $holding->id }}" class="hl sel">
                </td>        
                <td>{{ htmlspecialchars($holding->id,ENT_QUOTES) }}</td>        
                <td>{{ htmlspecialchars($holding->sys2,ENT_QUOTES) }}</td>        
                <td class="ocrr_ptrn">
                  {{ $holding -> patrn }}
                </td>       
                <td>{{ htmlspecialchars($holding->f245a,ENT_QUOTES) }} :: {{ htmlspecialchars($holding->f245b,ENT_QUOTES) }}</td>       
                <td>{{ htmlspecialchars($holding->library->code,ENT_QUOTES) }}</td>       
                <td>{{ htmlspecialchars($holding->f866a,ENT_QUOTES) }}</td>       
                <td>{{ htmlspecialchars($holding->f852b,ENT_QUOTES) }}</td>       
                <td>{{ htmlspecialchars($holding->f852h,ENT_QUOTES) }}</td> 
              </tr>
            @endforeach
          </tbody>
        </table>
    </div>
      <div class="modal-footer">
        <input type="hidden" name="holdingsset_id" value="{{ $holdingsset_id }}">
        <input type="hidden" name="update_hos" value="1">
        <input type="submit" name="submit" class="btn btn-primary" value="{{ trans('general.add_to_HOS') }}">
        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('general.close') }}</button>
      </div>
    </form>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->