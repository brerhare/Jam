<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'program-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	'type'=>'horizontal',
)); ?>

        <div class="control-group">
    	<label class="control-label" for="icon_path">Download</label>
           	<div class="controls">
           		<?php
    			// @@EG TbButton to redirect to another url. If you leave off the 'array()' you can put in any external url
				$this->widget('bootstrap.widgets.TbButton', array(
					'type'=>'primary',
					'label'=>'Download',
//					'url'=> array('/event/exportCSVFull/?fromD='.$fromD."&toD=".$toD."&fromT=".$fromT."&toT=".$toT),
					'url'=> array('/event/exportCSVFull/?fmday='.$fromD['mday'].'&fmon='.$fromD['mon'].'&fyear='.$fromD['year'].'&tmday='.$toD['mday'].'&tmon='.$toD['mon'].'&tyear='.$toD['year']),
				)); ?> &nbsp&nbsp&nbsp&nbspThe file is in CSV format
			</div>
		</div>

<?php $this->endWidget(); ?>

