<?php

$this->menu=array(
	array('label'=>'Create Download Collection','url'=>array('create')),
);

?>

<h2>Manage Download Collections</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'download-collection-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
		'name',

        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
        ),

	),
)); ?>
