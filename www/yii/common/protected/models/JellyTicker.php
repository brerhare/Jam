<?php

/**
 * This is the model class for table "jelly_ticker".
 *
 * The followings are the available columns in table 'jelly_ticker':
 * @property integer $id
 * @property integer $ticker
 * @property string $heading
 * @property string $text
 * @property string $url
 */
class JellyTicker extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JellyTicker the static model class
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
		return 'jelly_ticker';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('heading, text', 'required'),
			array('ticker', 'numerical', 'integerOnly'=>true),
			array('heading', 'length', 'max'=>255),
			array('url', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, ticker, heading, text, url', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'ticker' => 'Ticker',
			'heading' => 'Heading',
			'text' => 'Text',
			'url' => 'URL',
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
		$criteria->compare('ticker',$this->ticker);
		$criteria->compare('heading',$this->heading,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('url',$this->url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
