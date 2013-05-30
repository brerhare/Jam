<?php
/* @var $this PageController */
/* @var $model Page */

$this->menu=array(
	array('label'=>'Create Page', 'url'=>array('create')),
);


?>

<h1>Manage Pages</h1>


<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'page-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'title',
		'url',
		//'content',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
