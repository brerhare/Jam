<?php

$this->menu=array(
	array('label'=>'Create Event','url'=>array('create')),
);


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $('#event-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    return false;
});
");


?>

<h4>Manage Events</h4>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'event-grid',
	'dataProvider'=>$model->search(),
	////////////////////'dataProvider'=>$model->searchAllProgramsImAdminOrModFor(),
	'filter'=>$model,
	'columns'=>array(
		'id',

        array(
            'name'  => 'title',
            'value' => 'CHtml::link($data->title, Yii::app()->createUrl("event/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
			'htmlOptions' => array('style'=>'width:300px'),
        ),

        array(
            'name'  => 'start',
			'value'=> ' date("d-m-Y",strtotime($data->start))',
			'htmlOptions' => array('style'=>'width:90px'),
        ),

        array(
            'name'  => 'active',
			'htmlOptions' => array('style'=>'width:70px'),
        ),
		//'active:boolean',


/*
'buttonID' => array
(
    'label'=>'...',     //Text label of the button.
    'url'=>'...',       //A PHP expression for generating the URL of the button.
    'imageUrl'=>'...',  //Image URL of the button.
    'options'=>array(), //HTML options for the button tag.
    'click'=>'...',     //A JS function to be invoked when the button is clicked.
    'visible'=>'...',   //A PHP expression for determining whether the button is visible.
),
*/

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
			'htmlOptions' => array('style'=>'width:80px'),	// @@ CButtonColumn override width
            'template'=>'{update}{clone}{delete}',
            'buttons'=>array(
            	'update' => array(
					'icon'=>false,
                	'imageUrl'=>Yii::app()->request->baseUrl.'/img/edit.png',
            	),
            	'clone' => array(
                	'label'=>'Clone',
                	'imageUrl'=>Yii::app()->request->baseUrl.'/img/clone.jpg',
                	'url'=>'Yii::app()->controller->createUrl("event/clone", array("id"=>$data->primaryKey))',
            	),

				'delete'=>array(
					'icon'=>false,
					'imageUrl'=>Yii::app()->request->baseUrl.'/img/cross.png',
				),
			),
        ),
	),
)); ?>
