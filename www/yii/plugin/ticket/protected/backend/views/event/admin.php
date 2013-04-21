<?php

$this->menu=array(
	array('label'=>'Create Event','url'=>array('create')),
);

?>

<h1>Manage Events</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'event-grid',
	'dataProvider'=>$model->search(),
//	'filter'=>$model,
	'columns'=>array(
//		'id',
//		'uid',
		'title',
		'date',
		'active:boolean',
//		'active_start_date',
//		'active_start_time',
//		'active_end_date',
//		'active_end_time',

//		'address',
//		'post_code',
//		'ticket_logo_path',
//		'ticket_text',
//		'ticket_terms',
//		'ticket_vendor_id',

		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update} {delete}',
            'template'=>'{update}{ticketTypes}{areas}{delete}',
			'htmlOptions'=>array('width'=>'80px'),
            'buttons'=>array(
                'ticketTypes' => array(
                    'label'=>'Ticket Types',
                    'imageUrl'=>Yii::app()->request->baseUrl.'/img/ticketType.png',
                    'url'=>'Yii::app()->controller->createUrl("ticketType/session", array("event_id"=>$data->primaryKey))',
                ),
                'areas' => array(
                    'label'=>'Areas',
                    'imageUrl'=>Yii::app()->request->baseUrl.'/img/area.png',
                    'url'=>'Yii::app()->controller->createUrl("area/session", array("event_id"=>$data->primaryKey))',
                ),
            )


		),
	),
)); ?>
