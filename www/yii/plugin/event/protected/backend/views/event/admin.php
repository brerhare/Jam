<?php

$this->menu=array(
	array('label'=>'Create Event','url'=>array('create')),
);


?>

<h1>Manage Events</h1>



<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'event-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',

        array(
            'name'  => 'title',
            'value' => 'CHtml::link($data->title, Yii::app()->createUrl("event/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

		'start',
		'end',
		//'address',
		//'post_code',
		/*
		'web',
		'contact',
		'description',
		'thumb_path',
		'approved',
		'member_id',
		'program_id',
		*/
		array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{clone}{delete}',
            'buttons'=>array(
            	'clone' => array(
                	'label'=>'Clone',
                	'imageUrl'=>Yii::app()->request->baseUrl.'/img/copy.png',
                	'url'=>'Yii::app()->controller->createUrl("event/clone", array("id"=>$data->primaryKey))',
            	),
			),

        ),
	),
)); ?>
