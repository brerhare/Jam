<?php

$this->menu=array(
	array('label'=>'Create Column Content','url'=>array('create')),
);

?>

<h1>Manage Column Content</h1>


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
	'id'=>'jelly-column-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		// 'id',
		//'sequence',
// @@EG Clickable rows in grid view!
		array(
			'name'  => 'title',
			'value' => 'CHtml::link($data->title, Yii::app()->createUrl("jellyColumn/update",array("id"=>$data->primaryKey)))',
			'type'  => 'raw',
		),
		array(
			'name'  => 'url',
			'value' => 'CHtml::link($data->url, Yii::app()->createUrl("jellyColumn/update",array("id"=>$data->primaryKey)))',
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
