
<div class="carousel slide" id="carousel-example-generic" data-paused="true" >
  <div class="carousel-inner">
  	<?php $first=true ?>
  	<?php foreach ($holdings as $holding) { ?>
	    <div class="item <?= (!$first) ?: 'active' ?>">
	    	<?php $first=false ?>
				<dl class="dl-horizontal">
				  <dt>f245b</dt>
					<dd><?= $holding->f245b; ?></dd>
				  <dt>f245c</dt>
					<dd><?= $holding->f245c; ?></dd>
				  <dt>ocrr_ptrn</dt>
					<dd><?= $holding->ocrr_ptrn; ?></dd>
				  <dt>f022a</dt>
					<dd><?= $holding->f022a; ?></dd>
				  <dt>f260a</dt>
					<dd><?= $holding->f260a; ?></dd>
				  <dt>f260b</dt>
					<dd><?= $holding->f260b; ?></dd>
				  <dt>f710a</dt>
					<dd><?= $holding->f710a; ?></dd>
				  <dt>f780t</dt>
					<dd><?= $holding->f780t; ?></dd>
				  <dt>f362a</dt>
					<dd><?= $holding->f362a; ?></dd>
				  <dt>f866a</dt>
					<dd><?= $holding->f866a; ?></dd>
				  <dt>f866z</dt>
					<dd><?= $holding->f866z; ?></dd>
				  <dt>f310a</dt>
					<dd><?= $holding->f310a; ?></dd>
				</dl>
	    </div>
  	<?php } ?>
  </div>
  <a data-slide="prev" href="#carousel-example-generic" class="left carousel-control">
    <span class="icon-prev"></span>
  </a>
  <a data-slide="next" href="#carousel-example-generic" class="right carousel-control">
    <span class="icon-next"></span>
  </a>
</div>