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
/*            'template'=>'{update}{ticketTypes}{areas}{delete}',
            'buttons'=>array(
                'ticketTypes' => array(
                    'label'=>'Images',
                    'imageUrl'=>Yii::app()->request->baseUrl.'/img/image.png',
                    'url'=>'Yii::app()->controller->createUrl("image/session", array("product_id"=>$data->primaryKey))',
                ),
                'areas' => array(
                    'label'=>'Galleries',
                    'imageUrl'=>Yii::app()->request->baseUrl.'/img/camera.png',
                    'url'=>'Yii::app()->controller->createUrl("gallery/adminProduct", array("product_id"=>$data->primaryKey))',
                ),
            )
*/

		),
	),
)); ?>
