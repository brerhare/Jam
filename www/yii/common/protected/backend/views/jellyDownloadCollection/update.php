<?php
/* @var $this JellyDownloadCollectionController */
/* @var $model JellyDownloadCollection */

$this->breadcrumbs=array(
	'Jelly Download Collections'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List JellyDownloadCollection', 'url'=>array('index')),
	array('label'=>'Create JellyDownloadCollection', 'url'=>array('create')),
	array('label'=>'View JellyDownloadCollection', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage JellyDownloadCollection', 'url'=>array('admin')),
);
?>

<h1>Update JellyDownloadCollection <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>