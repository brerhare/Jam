<?php
/* @var $this DownloadCollectionController */
/* @var $model DownloadCollection */

$this->breadcrumbs=array(
	'Download Collections'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List DownloadCollection', 'url'=>array('index')),
	array('label'=>'Create DownloadCollection', 'url'=>array('create')),
	array('label'=>'View DownloadCollection', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage DownloadCollection', 'url'=>array('admin')),
);
?>

<h1>Update DownloadCollection <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>