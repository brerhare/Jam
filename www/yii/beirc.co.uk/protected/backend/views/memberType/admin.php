<?php

$this->menu=array(
	array('label'=>'Create Member Type','url'=>array('create')),
);

?>

<h2>Manage Member Types</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'member-type-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',

// @@EG Clickable rows in grid view!
        array(
            'name'  => 'description',
            'value' => 'CHtml::link($data->description, Yii::app()->createUrl("memberType/update",array("id"=>$data->primaryKey)))',
			'type'  => 'raw',
		),

		'slots',
		'week_month',
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
        ),
	),
)); ?>
