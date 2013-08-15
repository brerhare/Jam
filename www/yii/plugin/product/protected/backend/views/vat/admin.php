<?php

$this->menu=array(
	array('label'=>'Create Vat Rate','url'=>array('create')),
);

?>

<h2>Manage Vat Rates</h2>


<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'vat-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//id',
		//'uid',
		'description',
		'rate',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
			'htmlOptions'=>array('width'=>'80px'),

/*
            'buttons'=>array(           
                'update' => array(
                    //'label'=>'Update item',	// Hover text
                    //'icon'=>'plus',			// Change from default icon
                    'options'=>array(
                        'class'=>'btn btn-medium',
                    ),
                ),
                'delete' => array(
                	//'imageUrl'=>false,  // No image, use text on button
                    //'label'=>'delete item',
                    'options'=>array(
                        'class'=>'btn btn-medium',
                    ),
                ), 
            ),
*/

		),
	),
)); ?>
