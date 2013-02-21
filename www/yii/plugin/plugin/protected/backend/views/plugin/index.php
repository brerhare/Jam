<?php
/* @var $this PluginController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Plugins',
);

$this->menu=array(
	array('label'=>'Create Plugin', 'url'=>array('create')),
	array('label'=>'Manage Plugin', 'url'=>array('admin')),
);
?>

<h1>Plugins</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
