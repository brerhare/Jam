<?php

$this->menu=array(
	//array('label'=>'List Program','url'=>array('index')),
	array('label'=>'Create Program','url'=>array('create')),
);

?>

<h4>Manage Programs</h4>

<?php
/************************************************************************************/
// @@KIM
/*$isAdmin = new CActiveDataProvider('Program', array(
    'criteria'=>array(
    	'condition'=>'id=7',
    )
));*/

// $teacher is a CActiveRecord object representing one row in the teacher table,
// for the currently logged-in teacher:
/*$criteria=new CDbCriteria;
$criteria->compare('teacher_id', $teacher->id, false);
$isAdmin = new CActiveDataProvider('Student', array('criteria'=>$criteria));
?>*/

/*$isAdmin=new CActiveDataProvider('Member',array(
  'criteria'=>array(
    'with'=>array('member'),
    'condition'=>"member_id='$id'",
    'together'=>true,
  )
));*/

/*
$criteria=new CDbCriteria;
$criteria->addCondition("event_member_id = " . Yii::app()->session['eid']);
$criteria->addCondition("privilege_level = 4");	//@@TODO Privilege hardcoded
$isAdmin = new CActiveDataProvider('MemberHasProgram', array('criteria'=>$criteria));
$c = 0;
$memberHasPrograms = MemberHasProgram::model()->findAll($criteria);
*/

$img = Yii::app()->request->baseUrl.'/userdata/program/icon/'.'ws-logo-sm.jpg';

/*$isAdmin = new CActiveDataProvider('Program', array(
    'criteria'=>array(
        'with' => array('eventMembers'),
        'condition' => 'event_member_id=:id', 
    ),
));*/
/*$isAdmin = new CActiveDataProvider('Program', array(
    'criteria'=>array(
        'with' => array('eventMembers'),
        'condition' => 'event_program_id=:id', 
        'params' => array("id" => $model->id) 
    ),
));*/
/************************************************************************************/
?>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'program-grid',
	/////////////////////////////'dataProvider'=>$model->search(),
	//'dataProvider'=>$model->search(),
	'dataProvider'=>$model->searchAllProgramsImAdminFor(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		//'name',
		//'thumb_path',
		//'icon_path',
		//'event_program_fields_id',
        array(
            'name'  => 'name',
            'value' => 'CHtml::link($data->name, Yii::app()->createUrl("program/privilege",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{approve}{privilege}{update}{delete}',
'htmlOptions'=>array('width'=>'80px'),
            'buttons'=>array(
                'approve' => array(
                    'label'=>'Approve event submissions',
                    'imageUrl'=>Yii::app()->request->baseUrl.'/img/list.png',
                    //'url'=>'Yii::app()->controller->createUrl("program/approve", array("product_id"=>$data->primaryKey))',
                    'url'=>'Yii::app()->controller->createUrl("program/approve/" . $data->primaryKey)',
                ),
                'privilege' => array(
                    'label'=>'Assign privileges',
                    'imageUrl'=>Yii::app()->request->baseUrl.'/img/members.jpg',
                    //'url'=>'Yii::app()->controller->createUrl("program/privilege", array("product_id"=>$data->primaryKey))',
                    'url'=>'Yii::app()->controller->createUrl("program/privilege/" . $data->primaryKey)',
                ),
			),
		),
	),
)); ?>
