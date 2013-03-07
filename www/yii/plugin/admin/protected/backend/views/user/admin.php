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
		),
	),
)); ?>


