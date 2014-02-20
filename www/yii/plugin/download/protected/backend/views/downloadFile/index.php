<?php
/* @var $this DownloadFileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Download Files',
);

$this->menu=array(
	array('label'=>'Create DownloadFile', 'url'=>array('create')),
	array('label'=>'Manage DownloadFile', 'url'=>array('admin')),
);
?>

<h1>Download Files</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
