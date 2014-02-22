<?php
/* @var $this JellyDownloadCollectionController */
/* @var $model JellyDownloadCollection */

$this->breadcrumbs=array(
	'Jelly Download Collections'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List JellyDownloadCollection', 'url'=>array('index')),
	array('label'=>'Create JellyDownloadCollection', 'url'=>array('create')),
	array('label'=>'Update JellyDownloadCollection', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete JellyDownloadCollection', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage JellyDownloadCollection', 'url'=>array('admin')),
);
?>

<h1>View JellyDownloadCollection #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
