<?php
$this->breadcrumbs=array(
	'Members'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Member','url'=>array('index')),
	array('label'=>'Create Member','url'=>array('create')),
	array('label'=>'Update Member','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Member','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Member','url'=>array('admin')),
);
?>

<h1>View Member #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'password',
		'approved',
		'business_name',
		'address1',
		'address2',
		'address3',
		'address4',
		'postcode',
		'contact',
		'web',
		'email',
		'phone',
		'opening_hours',
		'html_content',
		'logo_path',
		'slider_image_path',
		'public',
	),
)); ?>
