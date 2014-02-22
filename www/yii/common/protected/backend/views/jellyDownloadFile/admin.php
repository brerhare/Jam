<?php

$this->menu=array(
	array('label'=>'Create Download File','url'=>array('create')),
);

?>

<h2>Manage Download Files</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'download-file-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		'filename',
		'description',
		//'download_collection_id',

        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
        ),

	),
)); ?>
