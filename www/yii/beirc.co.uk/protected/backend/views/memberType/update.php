<?php
$this->breadcrumbs=array(
	'Member Types'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List MemberType','url'=>array('index')),
	array('label'=>'Create MemberType','url'=>array('create')),
	array('label'=>'View MemberType','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage MemberType','url'=>array('admin')),
);
?>

<h1>Update MemberType <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>