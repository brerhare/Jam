<?php

$this->menu=array(
	array('label'=>'Create Article','url'=>array('create')),
);
?>


<h2>Manage Articles</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'article-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
		'date',
		'title',
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
