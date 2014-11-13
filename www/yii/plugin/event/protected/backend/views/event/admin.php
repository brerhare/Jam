<?php

$this->menu=array(
	array('label'=>'Create Event','url'=>array('create')),
);


?>

<h4>Manage Events</h4>



<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'event-grid',
	//'dataProvider'=>$model->search(),
	'dataProvider'=>$model->searchAllProgramsImAdminOrModFor(),
	//'filter'=>$model,
	'columns'=>array(
		'id',

        array(
            'name'  => 'title',
            'value' => 'CHtml::link($data->title, Yii::app()->createUrl("event/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
			'htmlOptions' => array('style'=>'width:390px'),
        ),

		'start',
		'active',
		//'end',
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
