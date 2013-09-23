<?php

$this->menu=array(
	array('label'=>'Create Department','url'=>array('create')),
);
?>


<h2>Manage Departments</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'department-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
		//'name',
        array(
            'name'  => 'name',
            'value' => 'CHtml::link($data->name, Yii::app()->createUrl("department/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{options}{features}{products}{delete}',
			'htmlOptions'=>array('width'=>'250px'),

// @@EG: buttons!
            'buttons'=>array( 
/*              'update' => array(
                    'options'=>array(
                        'class'=>'btn btn-medium',
                    ),
                ),*/
                'options' => array(
                	'imageUrl'=>false,
                    'label'=>'Options',
                    'url'=>'Yii::app()->createUrl("option/session", array("department_id"=>$data->id))',
                    'options'=>array(
                        'class'=>'btn btn-small',
                    ),
                ), 
                'features' => array(
                	'imageUrl'=>false,
                    'label'=>'Features',
                    'url'=>'Yii::app()->createUrl("feature/session", array("department_id"=>$data->id))',
                    'options'=>array(
                        'class'=>'btn btn-small',
                    ),
                ), 
                'products' => array(
                	'imageUrl'=>false,
                    'label'=>'Products',
                    'url'=>'Yii::app()->createUrl("product/session", array("department_id"=>$data->id))',
                    'options'=>array(
                        'class'=>'btn btn-small',
                    ),
                ), 
/*              'delete' => array(
                    'options'=>array(
                        'class'=>'btn btn-medium',
                    ),
                ), */
            ),
// End of EG


		),
	),
)); ?>


 
