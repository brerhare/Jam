<?php

/**
 * This is the model class for table "mailer_content".
 *
 * The followings are the available columns in table 'mailer_content':
 * @property integer $id
 * @property integer $uid
 * @property string $title
 * @property string $date
 * @property string $content
 * @property integer $sent
 *
 * The followings are the available model relations:
 * @property MailerList[] $mailerLists
 */
class MailerContent extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MailerContent the static model class
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
		return 'mailer_content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, title, date', 'required'),
			array('uid, sent', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('content', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, title, date, content, sent', 'safe', 'on'=>'search'),
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
			'mailerLists' => array(self::MANY_MANY, 'MailerList', 'mailer_content_has_mailer_list(mailer_content_id, mailer_list_id)'),
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
			'title' => 'Title',
			'date' => 'Date',
			'content' => 'Content',
			'sent' => 'Sent',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('sent',$this->sent);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

// @@EG CJuiDatePicker. See also the form where the widget is applied

    protected function afterFind(){
        parent::afterFind();
        $this->date=date('d-m-Y', strtotime(str_replace("-", "", $this->date)));
    }

    protected function beforeSave(){
        if(parent::beforeSave()){
            if (empty($this->date))
                $this->end = $this->date;
            $this->date=date('Y-m-d', strtotime(str_replace(",", "", $this->date)));
            return TRUE;
        }
       else
            return false;
    }

}
