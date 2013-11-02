<?php
$this->breadcrumbs=array(
	'Accordion Blocks',
);

$this->menu=array(
	array('label'=>'Create AccordionBlock','url'=>array('create')),
	array('label'=>'Manage AccordionBlock','url'=>array('admin')),
);
?>

<h1>Accordion Blocks</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
