<?php

/**
 * This is the model class for table "product_department".
 *
 * The followings are the available columns in table 'product_department':
 * @property integer $id
 * @property integer $uid
 * @property string $name
 * @property string $thumb_path
 * @property integer $active
 *
 * The followings are the available model relations:
 * @property Feature[] $features
 * @property Option[] $options
 * @property Product[] $products
 */
class Department extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Department the static model class
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
		return 'product_department';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, name', 'required'),
			array('uid, active', 'numerical', 'integerOnly'=>true),
			array('name, thumb_path', 'length', 'max'=>255),

            array('thumb_path', 'file','on'=>'insert',
                'types'=> 'jpg, jpeg, gif, png',
                'allowEmpty' => true,
                'maxSize' => 1024 * 1024 * 20, // 20MB
                'tooLarge' => 'The file was too large. Please upload a smaller file.'
            ),
            array('thumb_path', 'file','on'=>'update',
                'types'=> 'jpg, jpeg, gif, png',
                'allowEmpty' => true,
                'maxSize' => 1024 * 1024 * 20, // 20MB
                'tooLarge' => 'The file was too large. Please upload a smaller file.'
            ),
            array('thumb_path', 'unsafe'),
            array('thumb_path', 'length', 'max'=>255),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, name, thumb_path, active', 'safe', 'on'=>'search'),
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
			'features' => array(self::HAS_MANY, 'Feature', 'product_department_id'),
			'options' => array(self::HAS_MANY, 'Option', 'product_department_id'),
			'products' => array(self::HAS_MANY, 'Product', 'product_department_id'),
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
			'thumb_path' => 'Thumb (140w x 140h)',
			'active' => 'Active',
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
		$criteria->compare('thumb_path',$this->thumb_path,true);
		$criteria->compare('active',$this->active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,

            // @@EG: Change cgridview pagination
            'pagination' => array(
                'pageSize' => 50,
            ),

		));
	}
}
