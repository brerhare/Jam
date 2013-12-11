<?php

/**
 * This is the model class for table "mailer_content_has_mailer_list".
 *
 * The followings are the available columns in table 'mailer_content_has_mailer_list':
 * @property integer $mailer_content_id
 * @property integer $mailer_list_id
 */
class MailerContentHasList extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MailerContentHasList the static model class
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
		return 'mailer_content_has_mailer_list';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mailer_content_id, mailer_list_id', 'required'),
			array('mailer_content_id, mailer_list_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('mailer_content_id, mailer_list_id', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'mailer_content_id' => 'Mailer Content',
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

		$criteria->compare('mailer_content_id',$this->mailer_content_id);
		$criteria->compare('mailer_list_id',$this->mailer_list_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}