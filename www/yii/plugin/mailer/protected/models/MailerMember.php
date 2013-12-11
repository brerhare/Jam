<?php

/**
 * This is the model class for table "mailer_member".
 *
 * The followings are the available columns in table 'mailer_member':
 * @property integer $id
 * @property integer $uid
 * @property string $email_address
 * @property string $first_name
 * @property string $last_name
 * @property string $telephone
 * @property string $address
 * @property integer $active
 * @property integer $mailer_list_id
 *
 * The followings are the available model relations:
 * @property MailerList $mailerList
 */
class MailerMember extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MailerMember the static model class
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
		return 'mailer_member';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, email_address, mailer_list_id', 'required'),
			array('uid, active, mailer_list_id', 'numerical', 'integerOnly'=>true),
			array('email_address, first_name, last_name', 'length', 'max'=>255),
			array('telephone', 'length', 'max'=>45),
			array('address', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, email_address, first_name, last_name, telephone, address, active, mailer_list_id', 'safe', 'on'=>'search'),
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
			'mailerList' => array(self::BELONGS_TO, 'MailerList', 'mailer_list_id'),
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
			'email_address' => 'Email Address',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'telephone' => 'Telephone',
			'address' => 'Address',
			'active' => 'Subscribed',
			'mailer_list_id' => 'Mailer List',
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
		$criteria->compare('email_address',$this->email_address,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('active',$this->active);
		//$criteria->compare('mailer_list_id',$this->mailer_list_id);
		$criteria->addCondition("mailer_list_id = " . Yii::app()->session['list_id']);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
