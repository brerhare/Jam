<?php
/* @var $this DownloadCollectionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Download Collections',
);

$this->menu=array(
	array('label'=>'Create DownloadCollection', 'url'=>array('create')),
	array('label'=>'Manage DownloadCollection', 'url'=>array('admin')),
);
?>

<h1>Download Collections</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
