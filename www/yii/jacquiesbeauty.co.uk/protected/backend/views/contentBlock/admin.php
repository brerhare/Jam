<?php

$this->menu=array(
	array('label'=>'Create Page Content','url'=>array('create')),
);

?>

<h1>Manage Page Content</h1>


<?php
// @@EG: Treeview @@TODO: finish it
/*
$this->widget('CTreeView',array(
        'data'=>$dataTree,
        'animated'=>'fast', //quick animation
        'collapsed'=>'false',//remember must giving quote for boolean value in here
        'htmlOptions'=>array(
                'class'=>'treeview-blue',//there are some classes that ready to use
        ),

));
*/
?>


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
