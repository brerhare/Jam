<?php

/**
 * This is the model class for table "event_member".
 *
 * The followings are the available columns in table 'event_member':
 * @property integer $id
 * @property string $user_name
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $telephone
 * @property string $email_address
 * @property string $organisation
 * @property string $join_date
 * @property string $last_login_date
 * @property string $captcha
 * @property string $sid
 * @property string $avatar_path
 *
 * The followings are the available model relations:
 * @property EventEvent[] $eventEvents
 * @property EventProgram[] $eventPrograms
 */
class Member extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Member the static model class
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
		return 'event_member';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_name, password, first_name, last_name, email_address, join_date, last_login_date', 'required'),
			array('user_name, password, first_name, last_name, email_address, organisation, sid, avatar_path', 'length', 'max'=>255),
			array('telephone, captcha', 'length', 'max'=>45),

            array('avatar_path', 'file','on'=>'insert',
                'types'=> 'jpg, jpeg, gif, png',
                'allowEmpty' => true,
                'maxSize' => 1024 * 1024 * 20, // 1MB
                'tooLarge' => 'The file was bigger than 1MB. Please upload a smaller file.'
            ),
            array('avatar_path', 'file','on'=>'update',
                'types'=> 'jpg, jpeg, gif, png',
                'allowEmpty' => true,
                'maxSize' => 1024 * 1024 * 20, // 1MB
                'tooLarge' => 'The file was larger than 1MB. Please upload a smaller file.'
            ),
            array('avatar_path', 'unsafe'),
            array('avatar_path', 'length', 'max'=>255),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_name, password, first_name, last_name, telephone, email_address, organisation, join_date, last_login_date, captcha, sid, avatar_path', 'safe', 'on'=>'search'),
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
			'eventEvents' => array(self::HAS_MANY, 'EventEvent', 'member_id'),
			'eventPrograms' => array(self::MANY_MANY, 'EventProgram', 'event_member_has_event_program(event_member_id, event_program_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_name' => 'User Name',
			'password' => 'Password',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'telephone' => 'Telephone',
			'email_address' => 'Email Address',
			'organisation' => 'Organisation',
			'join_date' => 'Join Date',
			'last_login_date' => 'Last Login Date',
			'captcha' => 'Captcha',
			'sid' => 'SID for Ticketing',
			'avatar_path' => 'Icon Path',
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
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('email_address',$this->email_address,true);
		$criteria->compare('organisation',$this->organisation,true);
		$criteria->compare('join_date',$this->join_date,true);
		$criteria->compare('last_login_date',$this->last_login_date,true);
		$criteria->compare('captcha',$this->captcha,true);
		$criteria->compare('sid',$this->sid,true);
		$criteria->compare('avatar_path',$this->avatar_path,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}