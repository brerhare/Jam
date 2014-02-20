<?php
/* @var $this DownloadCollectionController */
/* @var $model DownloadCollection */

$this->breadcrumbs=array(
	'Download Collections'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List DownloadCollection', 'url'=>array('index')),
	array('label'=>'Manage DownloadCollection', 'url'=>array('admin')),
);
?>

<h1>Create DownloadCollection</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>