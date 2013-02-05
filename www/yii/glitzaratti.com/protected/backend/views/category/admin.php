<?php
/* @var $this CategoryController */
/* @var $model Category */

/*$this->breadcrumbs=array(
	'Categories'=>array('index'),
	'Manage',
);*/

$this->menu=array(
//	array('label'=>'List Category', 'url'=>array('index')),
	array('label'=>'Create Category', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#category-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Size Categories</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'category-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
        array(
            'name'=>'id',
            'htmlOptions'=>array('width'=>'40px'),
        ),
		'name',
		array(
			'class'=>'CButtonColumn',
			'htmlOptions'=>array('width'=>'80px'),
			'template'=>'{update}{sizes}{delete}',
			'buttons'=>array(
				'sizes' => array(
					'label'=>'Sizes',
					'imageUrl'=>Yii::app()->request->baseUrl.'/img/text_strikethrough.png',
					'url'=>'Yii::app()->controller->createUrl("size/session", array("category_id"=>$data->primaryKey))',
				),
			)

		),
	),
)); ?>
