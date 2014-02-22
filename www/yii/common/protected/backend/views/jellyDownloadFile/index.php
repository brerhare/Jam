<?php
/* @var $this JellyDownloadFileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Jelly Download Files',
);

$this->menu=array(
	array('label'=>'Create JellyDownloadFile', 'url'=>array('create')),
	array('label'=>'Manage JellyDownloadFile', 'url'=>array('admin')),
);
?>

<h1>Jelly Download Files</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
