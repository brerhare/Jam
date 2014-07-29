<?php

$this->menu=array(
	array('label'=>'Create Type','url'=>array('create')),
);
?>

<h2>Manage Types</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'food-type-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		'id',

// @@EG Clickable rows in grid view!
        array(
            'name'  => 'name',
            'value' => 'CHtml::link($data->name, Yii::app()->createUrl("foodType/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
        ),

	),
)); ?>
