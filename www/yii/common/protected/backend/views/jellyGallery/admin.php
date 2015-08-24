<?php
$this->menu=array(
	array('label'=>'Create Album','url'=>array('create')),
);

?>

<h2>Manage Gallery Albums</h2>

<style>
.imgClass { height:50px;}
</style>


<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'jelly-gallery-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',

        array(
            'name'  => 'sequence',
            'value' => 'CHtml::link($data->sequence, Yii::app()->createUrl("jellyGalleryImage/session",array("gallery_id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

        array(
            'name'  => 'title',
            'value' => 'CHtml::link($data->title, Yii::app()->createUrl("jellyGalleryImage/session",array("gallery_id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

		//'image',

        array('name'=>'image',
            'type'=>'html',
			'htmlOptions'=>array('width'=>'60px'),
            'header'=>'',
            'value'=> 'CHtml::image("/userdata/jelly/gallery/" . $data->image, "image", array("height"=>"50", "class"=>"imgClass"))',
        ),

/*array
(
        'class'=>'CButtonColumn',
        'deleteConfirmation'=>"js:'Do you really want to delete record with ID '+$(this).parent().parent().children(':nth-child(2)').text()+'?'",
),*/


		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{images}',
            'buttons'=>array(
                'images' => array(
                    'label'=>'Images',
                    'imageUrl'=>Yii::app()->request->baseUrl.'/img/image.png',
                    'url'=>'Yii::app()->controller->createUrl("jellyGalleryImage/session", array("gallery_id"=>$data->primaryKey))',
                ),
            ),

		),
		array (
        	'class'=>'CButtonColumn',
			'template'=>'{delete}',
			'buttons'=>array(
				'delete' => array (
					'label'=>'Delete',
					//'url'=>'...',       //A PHP expression for generating the URL of the button.
					//'imageUrl'=>'...',  //Image URL of the button.
					//'options'=>array(), //HTML options for the button tag.
					//'click'=>'...',     //A JS function to be invoked when the button is clicked.
					//'visible'=>'...',   //A PHP expression for determining whether the button is visible.
				),
			),
			//'deleteConfirmation'=>"js:'Record with ID '+$(this).parent().parent().children(':first-child').text()+' will be deleted! Continue?'",
			'deleteConfirmation'=>"js:'WARNING! This gallery album AND all its images will be deleted! Continue?'",
		),
	),
)); ?>
