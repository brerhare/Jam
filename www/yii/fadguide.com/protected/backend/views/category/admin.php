<?php

$this->menu=array(
	array('label'=>'Create Category','url'=>array('create')),
);
?>

<h2>Manage Categories</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'category-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		'id',

// @@EG Clickable rows in grid view!
        array(
            'name'  => 'username',
            'value' => 'CHtml::link($data->name, Yii::app()->createUrl("category/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
        ),

	),
)); ?>
