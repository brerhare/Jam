<?php

$this->menu=array(
	array('label'=>'Manage Accordion Blocks','url'=>array('admin')),
);
?>

<h1>Create Accordion Block</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>