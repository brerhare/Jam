<?php
$this->breadcrumbs=array(
	'Download Files'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List DownloadFile','url'=>array('index')),
	array('label'=>'Create DownloadFile','url'=>array('create')),
	array('label'=>'Update DownloadFile','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete DownloadFile','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage DownloadFile','url'=>array('admin')),
);
?>

<h1>View DownloadFile #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'filename',
		'description',
		'download_collection_id',
	),
)); ?>
