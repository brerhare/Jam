<?php
$this->breadcrumbs=array(
	'Member Types'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List MemberType','url'=>array('index')),
	array('label'=>'Manage MemberType','url'=>array('admin')),
);
?>

<h1>Create MemberType</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>