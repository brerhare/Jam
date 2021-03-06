<?php

/**
 * This is the model class for table "admin_user".
 *
 * The followings are the available columns in table 'admin_user':
 * @property integer $id
 * @property string $email_address
 * @property string $password
 * @property string $display_name
 * @property string $sid
 *
 * The followings are the available model relations:
 * @property Plugin[] $adminPlugins
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'admin_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email_address, password, display_name, sid', 'required'),
			array('email_address, password, display_name', 'length', 'max'=>128),
			array('sid', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email_address, password, display_name, sid', 'safe', 'on'=>'search'),
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
			'adminPlugins' => array(self::MANY_MANY, 'Plugin', 'admin_user_has_plugin(user_id, plugin_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email_address' => 'User name',
			'password' => 'Password',
			'display_name' => 'Display Name',
			'sid' => 'Sid',
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
		$criteria->compare('email_address',$this->email_address,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('display_name',$this->display_name,true);
		$criteria->compare('sid',$this->sid,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,

            // @@EG: Change cgridview pagination
            'pagination' => array(
                'pageSize' => 50,
            ),

		));
	}
}
