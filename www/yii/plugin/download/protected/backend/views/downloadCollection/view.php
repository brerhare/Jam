<?php
/* @var $this DownloadCollectionController */
/* @var $model DownloadCollection */

$this->breadcrumbs=array(
	'Download Collections'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List DownloadCollection', 'url'=>array('index')),
	array('label'=>'Create DownloadCollection', 'url'=>array('create')),
	array('label'=>'Update DownloadCollection', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete DownloadCollection', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage DownloadCollection', 'url'=>array('admin')),
);
?>

<h1>View DownloadCollection #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
