<?php
/* @var $this DownloadFileController */
/* @var $model DownloadFile */

$this->breadcrumbs=array(
	'Download Files'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List DownloadFile', 'url'=>array('index')),
	array('label'=>'Manage DownloadFile', 'url'=>array('admin')),
);
?>

<h1>Create DownloadFile</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>