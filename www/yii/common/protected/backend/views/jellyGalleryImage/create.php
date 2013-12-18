<?php
$this->breadcrumbs=array(
	'Jelly Gallery Images'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List JellyGalleryImage','url'=>array('index')),
	array('label'=>'Manage JellyGalleryImage','url'=>array('admin')),
);
?>

<h1>Create JellyGalleryImage</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>