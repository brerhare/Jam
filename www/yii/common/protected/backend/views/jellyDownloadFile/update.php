<?php
/* @var $this JellyDownloadFileController */
/* @var $model JellyDownloadFile */

$this->breadcrumbs=array(
	'Jelly Download Files'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List JellyDownloadFile', 'url'=>array('index')),
	array('label'=>'Create JellyDownloadFile', 'url'=>array('create')),
	array('label'=>'View JellyDownloadFile', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage JellyDownloadFile', 'url'=>array('admin')),
);
?>

<h1>Update JellyDownloadFile <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>