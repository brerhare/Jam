<?php
/*
$this->breadcrumbs=array(
	'Areas'=>array('index'),
	'Manage',
);
*/

$this->menu=array(
	array('label'=>'Back to Manage Events', 'url'=>array('event/admin')),
	array('label'=>'Create Area','url'=>array('create')),
);

/*
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('area-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
*/
?>

<h4>Manage Areas for 
<?php
    $model_event = Event::model()->findByPk(Yii::app()->session['event_id']);
    echo $model_event->title; 
?> 
</h4>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'area-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
		'description',
		'max_places',
		//'ticket_event_id',
		
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update} {delete}',
            'template'=>'{update}{ticketTypes}{delete}',
			'htmlOptions'=>array('width'=>'80px'),
            'buttons'=>array(
                'ticketTypes' => array(
                    'label'=>'Ticket Types',
                    'imageUrl'=>Yii::app()->request->baseUrl.'/img/ticketType.png',
                    'url'=>'Yii::app()->controller->createUrl("ticketType/session", array("area_id"=>$data->primaryKey))',
                ),
            )
		),
	),
)); ?>
