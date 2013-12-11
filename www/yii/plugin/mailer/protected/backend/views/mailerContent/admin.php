<?php
$this->menu=array(
	array('label'=>'Create Mail Content','url'=>array('create')),
);

?>

<h2>Manage Mail Content</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'mailer-content-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',

        array(
            'name'  => 'title',
            'value' => 'CHtml::link($data->title, Yii::app()->createUrl("mailerContent/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

        array(
            'name'  => 'date',
            'value' => 'CHtml::link($data->date, Yii::app()->createUrl("mailerContent/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

		//'content',
		'sent',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
