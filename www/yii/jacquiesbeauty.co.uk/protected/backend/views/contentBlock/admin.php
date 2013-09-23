<?php

$this->menu=array(
	array('label'=>'Create Page Content','url'=>array('create')),
);

?>

<h1>Manage Page Content</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'content-block-grid',
	'dataProvider'=>$model->search(),
	// 'filter'=>$model,
	'columns'=>array(
		// 'id',
		//'sequence',
// @@EG Clickable rows in grid view!
		array(
			'name'  => 'title',
			'value' => 'CHtml::link($data->title, Yii::app()->createUrl("contentBlock/update",array("id"=>$data->primaryKey)))',
			'type'  => 'raw',
		),
		array(
			'name'  => 'url',
			'value' => 'CHtml::link($data->url, Yii::app()->createUrl("contentBlock/update",array("id"=>$data->primaryKey)))',
			'type'  => 'raw',
		),
		'active:boolean',
		// 'content',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
