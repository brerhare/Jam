<?php

/**
 * This is the model class for table "product_feature".
 *
 * The followings are the available columns in table 'product_feature':
 * @property integer $id
 * @property integer $uid
 * @property string $name
 * @property integer $product_department_id
 *
 * The followings are the available model relations:
 * @property Department $productDepartment
 * @property Product[] $productProducts
 */
class Feature extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Feature the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'product_feature';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, name, product_department_id', 'required'),
			array('uid, product_department_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, name, product_department_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'productDepartment' => array(self::BELONGS_TO, 'Department', 'product_department_id'),
			'productProducts' => array(self::MANY_MANY, 'Product', 'product_product_has_product_feature(product_feature_id, product_product_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'uid' => 'Uid',
			'name' => 'Name',
			'product_department_id' => 'Product Department',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		//$criteria->compare('uid',$this->uid);
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$criteria->compare('name',$this->name,true);
		//$criteria->compare('product_department_id',$this->product_department_id);
		$criteria->addCondition("product_department_id = " . Yii::app()->session['department_id']);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
