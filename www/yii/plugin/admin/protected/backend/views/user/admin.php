<?php

$this->menu=array(
	array('label'=>'Create User','url'=>array('create')),
);
?>

<h1>Manage Users</h1>

<?php
$gridColumns=array(
	'id',

	array(
		'class' => 'bootstrap.widgets.TbEditableColumn',
		'name' => 'email_address',
		'editable' => array(
			'url' => $this->createUrl('user/editable'),
			'placement' => 'right',
			'inputclass' => 'span3'
		)
	),

	array(
		'class' => 'bootstrap.widgets.TbEditableColumn',
		'name' => 'password',
		'editable' => array(
			'url' => $this->createUrl('user/editable'),
			'placement' => 'right',
			'inputclass' => 'span3'
		)
	),

	array(
		'class' => 'bootstrap.widgets.TbEditableColumn',
		'name' => 'display_name',
		'editable' => array(
			'url' => $this->createUrl('user/editable'),
			'placement' => 'right',
			'inputclass' => 'span3'
		)
	),

	// 'sid',

	array(
		'header'=>'Options',
		'class'=>'bootstrap.widgets.TbButtonColumn',
		'buttons'=>array(
			'view'=>
			array(
				'url'=>'Yii::app()->createUrl("user/view", array("id"=>$data->id))',
				'options'=>array(
					'ajax'=>array(
						'type'=>'POST',
						'url'=>"js:$(this).attr('href')",
						'success'=>'function(data) { $("#viewModal .modal-body p").html(data); $("#viewModal").modal(); }'
					),
				),
			),
			'update'=>
			array(
				'url'=>'Yii::app()->createUrl("user/update", array("id"=>$data->id))',
				'options'=>array(
					'ajax'=>array(
						'type'=>'POST',
						'url'=>"js:$(this).attr('href')",
						'success'=>'function(data) { $("#viewModal .modal-body p").html(data); $("#viewModal").modal(); }'
					),
				),
			),
		),
	)
);

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'type'=>'bordered',
	'dataProvider'=>$model->search(),
	'template'=>"{items}",
	'columns'=>$gridColumns,
));
?>



<!-- View Popup  -->
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'viewModal')); ?>
<!-- Popup Header -->
<div class="modal-header">
    <h4>View User Details</h4>
</div>
<!-- Popup Content -->
<div class="modal-body">
    <p>User Details</p>
</div>
<!-- Popup Footer -->
<div class="modal-footer">

    <!-- close button -->
	<?php $this->widget('bootstrap.widgets.TbButton', array(
	'label'=>'Close',
	'url'=>'#',
	'htmlOptions'=>array('data-dismiss'=>'modal'),
)); ?>
    <!-- close button ends-->
</div>
<?php $this->endWidget(); ?>
<!-- View Popup ends -->
