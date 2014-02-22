<?php
/* @var $this JellyDownloadCollectionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Jelly Download Collections',
);

$this->menu=array(
	array('label'=>'Create JellyDownloadCollection', 'url'=>array('create')),
	array('label'=>'Manage JellyDownloadCollection', 'url'=>array('admin')),
);
?>

<h1>Jelly Download Collections</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
