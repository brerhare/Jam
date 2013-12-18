<?php
$this->breadcrumbs=array(
	'Jelly Galleries'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List JellyGallery','url'=>array('index')),
	array('label'=>'Manage JellyGallery','url'=>array('admin')),
);
?>

<h1>Create JellyGallery</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>