<?php
/* @var $this JellyDownloadCollectionController */
/* @var $model JellyDownloadCollection */

$this->breadcrumbs=array(
	'Jelly Download Collections'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List JellyDownloadCollection', 'url'=>array('index')),
	array('label'=>'Manage JellyDownloadCollection', 'url'=>array('admin')),
);
?>

<h1>Create JellyDownloadCollection</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>