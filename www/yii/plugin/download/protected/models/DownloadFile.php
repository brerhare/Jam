<?php

/**
 * This is the model class for table "download_file".
 *
 * The followings are the available columns in table 'download_file':
 * @property integer $id
 * @property string $filename
 * @property string $description
 * @property integer $download_collection_id
 *
 * The followings are the available model relations:
 * @property DownloadCollection $downloadCollection
 */
class DownloadFile extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DownloadFile the static model class
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
		return 'download_file';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('filename, download_collection_id', 'required'),
			array('download_collection_id', 'numerical', 'integerOnly'=>true),
			array('filename, description', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, filename, description, download_collection_id', 'safe', 'on'=>'search'),
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
			'downloadCollection' => array(self::BELONGS_TO, 'DownloadCollection', 'download_collection_id'),
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
			'download_collection_id' => 'Download Collection',
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
		$criteria->compare('download_collection_id',$this->download_collection_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}