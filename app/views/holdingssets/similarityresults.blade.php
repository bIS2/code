<?php $librarianclass = ' '.substr($hol->sys2, 0, 4); ?>
<div class="modal-dialog{{ $librarianclass }}">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h3 class="modal-title"><?= $hol->f245a.' :: '.htmlspecialchars($hol->f245b,ENT_QUOTES); ?></h3>
    </div>
    <form id="recalled" method="put" action="{{ url('sets/new-ho-s') }}/{{ $holdingsset_id }}" data-remote="true">

      <div class="modal-body">
      <div class="row">
        <div class="col-xs-6">
          <?php if ($holdings->count() > 0) { ?>
            <table class="table table-striped table-condensed flexme">
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
                    $btnlock  = ($holding->locked()->exists()) ? 'btn-warning ' : ''; 
                    $trclass  = ($holding->locked()->exists()) ? 'locked' : '';
                    $ownertrclass = ($holding->is_owner == 't') ? ' is_owner' : '';
                    $auxtrclass   = ($holding->is_aux == 't') ? ' is_aux' : ''; 
                    $librarianclass = ' '.substr($holding->sys2, 0, 4);
                  ?>
                  <tr id="holding{{ $holding -> id; }}" class="{{ $trclass }}{{ $ownertrclass }}{{ $auxtrclass }}{{ $preftrclass }}{{ $librarianclass }}{{ ($holding->is_annotated) ? ' text-warning' : '' }}">     
                    <td>              
                      <input id="holding_id" name="holding_id[]" type="checkbox" value="{{ $holding->id }}" class="hl sel">
                    </td>        
                    <td>{{ htmlspecialchars($holding->id,ENT_QUOTES) }}</td>        
                    <td>{{ htmlspecialchars($holding->sys2,ENT_QUOTES) }}</td>        
                    <td class="ocrr_ptrn">
                      {{ $holding -> patrn_no_btn }}
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
          <?php } 
          else { ?>
            <h2 class="text-info"><span class="fa fa-info-circle text-danger"></span> {{ trans('holdingssets.no_results_to_show') }}</h2>
          <?php } ?>
        </div>
        <div class="col-xs-6">
          <?php var_dump($res); ?>
        </div>
        </div>
      </div>

      <div class="modal-footer">
        <input type="hidden" name="holdingsset_id" value="{{ $holdingsset_id }}">
        <input type="hidden" name="update_hos" value="1">
        <?php if ($holdings->count() > 0) { ?>
          <input type="submit" name="submit" class="btn btn-primary" data-disable-with="..." value="{{ trans('general.add_to_HOS') }}">
        <?php } ?>
        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('general.close') }}</button>
      </div>
    </form>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->