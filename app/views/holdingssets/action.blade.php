   <?php
   
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
    ?>

    <div class="btn-group actions-menu pull-left" data-container="body">
      <?php if (Auth::user()->hasRole('resuser')) : ?>
        <?php if ($holding->locked()->exists()) : ?>
          <div class="btn btn-default btn-xs">
            <a id="holding<?= $holding -> id; ?>lock" set="<?=$holdingsset->id; ?>" href="<?= route('lockeds.store',['holding_id' => $holding->id]); ?>" class="pop-over <?= $btnlock; ?> pull-right" data-remote="true" data-method="post" data-params="holdingsset_id=<?=$holdingsset->id; ?>"  data-disable-with="..." data-content="<strong><?= trans('holdingssets.reserved_by'); ?> </strong><?= $holding->locked->user->name; ?><br><strong><?= trans('holdingssets.on_behalf_of'); ?></strong> <?= $holding->locked->comments; ?>" data-placement="right" data-toggle="popover" data-html="true" data-trigger="hover"><span class="glyphicon glyphicon-lock"></span></a>
          </div>
        <?php else : ?>
          <div class="btn btn-default btn-xs">
            <a id="holding<?= $holding -> id; ?>lock" set="<?=$holdingsset->id; ?>" href="#" class="editable  pull-right" data-type="text" data-pk="<?=$holdingsset->id; ?>" data-url="<?= route('lockeds.update',[$holding->id]); ?>" title="<?php if ($btn != 'btn-success disabled') : ?> <?= trans('holdingssets.lock_hol'); ?> @else <?= trans('holdingssets.unlock_hol'); ?><?php endif ?>"><span class="glyphicon glyphicon-lock"></span></a>
          </div>
        <?php endif ?>
      <?php elseif($holding->locked) : ?>
        <div class="btn btn-default btn-xs">
          <a id="holding<?= $holding -> id; ?>lock" class="pop-over <?= $btnlock; ?> pull-right" data-content="<strong><?= trans('holdingssets.reserved_by'); ?> </strong><?= $holding->locked->user->name; ?><br><strong><?= trans('holdingssets.on_behalf_of'); ?></strong> <?= $holding->locked->comments; ?>" data-placement="right" data-toggle="popover" data-html="true" data-trigger="hover"><span class="glyphicon glyphicon-lock"></span></a>
        </div>
      <?php endif ?>
    
      <?php if (!($HOSconfirm) && !($HOSincorrect) && !($holding->locked)) : ?>
        <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown">
          <?= trans('general.action'); ?> <i class="fa  fa-caret-right"></i>
        </button>
        <ul class="fa fa-careft-left dropdown-menu" role="menu">
          <?php if (!($holding->locked)) : ?>
            <li>
              <a href="<?= route('holdings.show', $holding->id); ?>" data-target="#modal-show" data-toggle="modal" class="pop-over" data-content="<strong><?= trans('holdingssets.see_more_information'); ?></strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><span class="glyphicon glyphicon-eye-open"></span></a>
            </li>
            <li>
              <a href="/sets/from-library/<?= $holding->id; ?>" set="<?=$holdingsset->id; ?>" data-target="#modal-show" data-toggle="modal" class="pop-over" data-content="<strong><?= trans('holdingssets.see_information_from_original_system'); ?></strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><span class="fa fa-external-link"></span></a>
            </li>
            <li>
              <a id="holding<?=$holding -> id;; ?>delete" set="<?=$holdingsset->id; ?>"  href="<?= action('HoldingssetsController@putNewHOS',[$holding->id]); ?>" data-remote="true" data-method="put" data-params="holdingsset_id=<?=$holdingsset->id; ?>" data-disable-with="..." class="pop-over" data-content="<strong><?= trans('holdingssets.remove_from_HOS'); ?></strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><span class="fa fa-times"></span></a>
            </li>
            <li>
              <a href="/sets/recall-holdings/<?= $holding->id; ?>" set="<?=$holdingsset->id; ?>" data-target="#modal-show" data-toggle="modal" class="pop-over" data-content="<strong><?= trans('holdingssets.recall_hos_from_this_holding'); ?></strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><span class="fa fa-crosshairs"></span></a>
            </li>
            <li>
              <a href="/sets/similarity-search/<?= $holding->id; ?>" set="<?=$holdingsset->id; ?>" data-target="#modal-show" data-toggle="modal" class="pop-over" data-content="<strong><?= trans('holdingssets.similarity_search_from_this_holding'); ?></strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><span class="fa fa-search"></span></a>
            </li>
            <?php if ($ownertrclass == '') : ?>
              <li>
                <a id="holding<?=$holding -> id;; ?>forceowner" set="<?=$holdingsset->id; ?>" href="<?= action('HoldingssetsController@putForceOwner',[$holding->id]); ?>" data-remote="true" data-method="put" data-params="holdingsset_id=<?=$holdingsset->id; ?>" data-disable-with="..." data-disable-with="..." class="pop-over" data-content="<strong><?= trans('holdingssets.force_owner'); ?></strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><span class="fa fa-stop text-danger"></span></a>
              </li>
            <?php endif ?>
            <li>
              <a id="holding<?=$holding -> id;; ?>forceaux" set="<?=$holdingsset->id; ?>" href="<?= action('HoldingssetsController@putForceAux',[$holding->id]); ?>?unique_aux=1&holdingsset_id=<?= $holdingsset->id; ?>&ptrn=<?= $holding->aux_ptrn; ?>" data-remote="true" data-method="put" data-params="holdingsset_id=<?=$holdingsset->id; ?>" data-disable-with="..." data-disable-with="..." class="forceaux pop-over" data-content="<strong><?= trans('holdingssets.force_aux'); ?></strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><span class="fa fa-stop text-warning"></span></a>
            </li>
            <li>
              <a id="holding<?=$holding -> id;; ?>forceblue" set="<?=$holdingsset->id; ?>" href="<?= action('HoldingssetsController@putForceBlue',[$holding->id]); ?>" data-remote="true" data-method="put" data-params="holdingsset_id=<?=$holdingsset->id; ?>" data-disable-with="..." data-disable-with="..." class="forceblue pop-over" data-content="<strong><?= trans('holdingssets.force_blue'); ?></strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><span class="fa fa-stop text-primary"></span></a>
            </li>
          <?php endif ?>
          <?php if ($holding->is_annotated) : ?>
            <li>
              <a href="<?= route('notes.create',['holding_id'=>$holding->id, 'consult' => '1']); ?>" data-toggle="modal" data-target="#form-create-notes" class="btn-link btn-xs btn-tag pop-over" data-content="<strong><?= trans('holdingssets.see_storeman_annotations'); ?></strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover">
                <span class="fa fa-tags text-danger"></span></a>
              </li>
            <?php endif ?>
          </ul>
          <?php  ?>
        <?php elseif ($holding->is_annotated) : ?>
          <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown">
            <?= trans('general.action'); ?> <i class="fa  fa-caret-right"></i>
          </button>
          <ul class="fa fa-careft-left dropdown-menu" role="menu">
            <li>
              <a href="<?= route('notes.create',['holding_id'=>$holding->id, 'consult' => '1']); ?>" data-toggle="modal" data-target="#form-create-notes" class="btn-link btn-xs btn-tag pop-over" data-content="<strong><?= trans('holdingssets.see_storeman_annotations'); ?></strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover">
                <span class="fa fa-tags text-danger"></span>
              </a>
            </li>
          </ul>
        <?php endif ?>
      </div>
      <?php }
