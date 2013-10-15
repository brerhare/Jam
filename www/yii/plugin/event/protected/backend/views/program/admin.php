<?php

$this->menu=array(
	//array('label'=>'List Program','url'=>array('index')),
	array('label'=>'Create Program','url'=>array('create')),
);

?>

<h1>Manage Programs</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'program-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		//'name',
		//'thumb_path',
		//'icon_path',
		//'event_program_fields_id',
        array(
            'name'  => 'name',
            'value' => 'CHtml::link($data->name, Yii::app()->createUrl("program/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{images}{delete}',
            'buttons'=>array(
                'images' => array(
                    'label'=>'Assign privileges',
                    'imageUrl'=>Yii::app()->request->baseUrl.'/img/lock.png',
                    //'url'=>'Yii::app()->controller->createUrl("program/privilege", array("product_id"=>$data->primaryKey))',
                    'url'=>'Yii::app()->controller->createUrl("program/privilege/" . $data->primaryKey)',
                ),
			),
		),
	),
)); ?>
