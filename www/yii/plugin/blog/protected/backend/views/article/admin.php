<?php

$this->menu=array(
	array('label'=>'Create Article','url'=>array('create')),
);
?>


<h2>Manage Articles</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'article-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
		array(
		'name' => 'date',
		'value' => '$data->date',
		'htmlOptions'=>array('width'=>'80'),
		),
        array(
            'name'  => 'title',
            'value' => 'CHtml::link($data->title, Yii::app()->createUrl("article/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

		//'intro',
		//'content',
		/*
		'blog_category_id',
		*/
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
        ),

	),
)); ?>
