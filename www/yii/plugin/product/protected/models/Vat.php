<?php

/**
 * This is the model class for table "product_vat".
 *
 * The followings are the available columns in table 'product_vat':
 * @property integer $id
 * @property integer $uid
 * @property string $description
 * @property string $rate
 * @property integer $is_default
 *
 * The followings are the available model relations:
 * @property Product[] $products
 */
class Vat extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Vat the static model class
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
		return 'product_vat';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, description', 'required'),
			array('uid, is_default', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>255),
			array('rate', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, description, rate, is_default', 'safe', 'on'=>'search'),
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
			'products' => array(self::HAS_MANY, 'Product', 'product_vat_id'),
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
			'description' => 'Description',
			'rate' => 'Rate %',
			'is_default' => 'Default rate?',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('rate',$this->rate,true);
		$criteria->compare('is_default',$this->is_default,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
