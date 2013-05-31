<?php
/* @var $this PageController */
/* @var $model Page */

$this->menu=array(
	array('label'=>'Manage Pages', 'url'=>array('admin')),
);
?>

<h1>Create Page</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>