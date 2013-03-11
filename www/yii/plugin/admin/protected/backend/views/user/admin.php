<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List User','url'=>array('index')),
	array('label'=>'Create User','url'=>array('create')),
)?>


<h1>Manage Users</h1>

<!----------------------------------------------------------------------------------------------------------------->
<br/>
<h4>Inline edit with separate page for CRUD</h4>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'user-grid',
    'type'=>'striped bordered',
	'dataProvider'=>$model->search(),
	/*'filter'=>$model,*/
	'columns'=>array(
		'id',
		'kid',

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

		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'header'=>'Options',
		),
	),
)); ?>


<!----------------------------------------------------------------------------------------------------------------->
<br/>
<h4>Modal dialog for CRUD</h4>

<?php
	$gridColumns=array(
	'id',
	'kid',

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

	array(
		'class'=>'bootstrap.widgets.TbButtonColumn',
		'header'=>'Options',
		// This next calls the modal/dropdown/other
		'htmlOptions' => array(
			'data-toggle'=>'modal',
			'data-target'=>'#myModal'
		),
		'viewButtonUrl'=>null,
		'updateButtonUrl'=>null,
		'deleteButtonUrl'=>null,
	),
);

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'type'=>'bordered',
	'dataProvider'=>$model->search(),
	'template'=>"{items}",
	'columns'=>$gridColumns,
));
?>

<!-- View Popup  -->
<?php
$this->beginWidget('bootstrap.widgets.TbModal', array(
	'id'=>'myModal',
	'options' => array(
		'title'=>'userTitle',
		'autoOpen'=>false,
		'modal'=>true, /* this makes the dialog, appear on a overlay */
		'width'=>500,
		'height'=>280,
		'minHeight'=>400,
		'autoOpen'=>false,
		'bgiframe'=>true,
		'draggable'=>true,
		'resizable'=>true,
		'closeOnEscape'=>true,
	),
)); ?>

<!-- Popup Header -->
<div class="modal-header">
    <h4>User Details</h4>
</div>

<!-- Popup Content -->
<div class="modal-body">
    <p>User Details</p>
	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>


<!-- Popup Footer -->
<div class="modal-footer">

    <!-- save button -->
	<?php  $this->widget('bootstrap.widgets.TbButton', array(
	'type'=>'primary',
	'label'=>'Save',
	'url'=>'#',
	'htmlOptions'=>array('data-dismiss'=>'modal'),
));  ?>
    <!-- save button end-->

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

