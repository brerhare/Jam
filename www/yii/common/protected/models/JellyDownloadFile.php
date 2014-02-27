<?php

/**
 * This is the model class for table "jelly_download_file".
 *
 * The followings are the available columns in table 'jelly_download_file':
 * @property integer $id
 * @property string $filename
 * @property string $description
 * @property integer $jelly_download_collection_id
 *
 * The followings are the available model relations:
 * @property JellyDownloadCollection $jellyDownloadCollection
 */
class JellyDownloadFile extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JellyDownloadFile the static model class
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
		return 'jelly_download_file';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('filename, jelly_download_collection_id', 'required'),
			array('jelly_download_collection_id', 'numerical', 'integerOnly'=>true),
			array('filename, description', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, filename, description, jelly_download_collection_id', 'safe', 'on'=>'search'),
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
			'jellyDownloadCollection' => array(self::BELONGS_TO, 'JellyDownloadCollection', 'jelly_download_collection_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'filename' => 'Filename',
			'description' => 'Description',
			'jelly_download_collection_id' => 'Jelly Download Collection',
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
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('jelly_download_collection_id',$this->jelly_download_collection_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}