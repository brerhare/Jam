<?php
/* @var $this JellyDownloadFileController */
/* @var $model JellyDownloadFile */

$this->breadcrumbs=array(
	'Jelly Download Files'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List JellyDownloadFile', 'url'=>array('index')),
	array('label'=>'Create JellyDownloadFile', 'url'=>array('create')),
	array('label'=>'Update JellyDownloadFile', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete JellyDownloadFile', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage JellyDownloadFile', 'url'=>array('admin')),
);
?>

<h1>View JellyDownloadFile #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'filename',
		'description',
		'jelly_download_collection_id',
	),
)); ?>
