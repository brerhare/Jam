<?php
/* @var $this DownloadFileController */
/* @var $model DownloadFile */

$this->breadcrumbs=array(
	'Download Files'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List DownloadFile', 'url'=>array('index')),
	array('label'=>'Create DownloadFile', 'url'=>array('create')),
	array('label'=>'View DownloadFile', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage DownloadFile', 'url'=>array('admin')),
);
?>

<h1>Update DownloadFile <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>