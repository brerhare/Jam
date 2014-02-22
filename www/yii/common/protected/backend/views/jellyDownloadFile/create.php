<?php
/* @var $this JellyDownloadFileController */
/* @var $model JellyDownloadFile */

$this->breadcrumbs=array(
	'Jelly Download Files'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List JellyDownloadFile', 'url'=>array('index')),
	array('label'=>'Manage JellyDownloadFile', 'url'=>array('admin')),
);
?>

<h1>Create JellyDownloadFile</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>