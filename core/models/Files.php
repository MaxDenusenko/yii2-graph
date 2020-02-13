<?php

namespace app\core\models;

use app\core\forms\FileSettingForm;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;
use yiidreamteam\upload\FileUploadBehavior;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property int $datum_id
 * @property string $file
 *
 * @property FileSettings[] $fileSettings
 * @property Datums $datum
 */
class Files extends ActiveRecord
{
    public function setSetting(FileSettingForm $settings)
    {
        $setting = new FileSettings($settings);
        $this->fileSettings = $setting;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%files}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            [['datum_id', 'file'], 'required'],
//            [['datum_id'], 'integer'],
//            [['file'], 'string', 'max' => 255],
//            [['datum_id'], 'exist', 'skipOnError' => true, 'targetClass' => Datums::className(), 'targetAttribute' => ['datum_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'datum_id' => 'Datum',
            'file' => 'File',
        ];
    }

    /**
     * Gets query for [[FileSettings]].
     *
     * @return ActiveQuery
     */
    public function getFileSettings()
    {
        return $this->hasMany(FileSettings::className(), ['file_id' => 'id']);
    }

    /**
     * Gets query for [[Datum]].
     *
     * @return ActiveQuery
     */
    public function getDatum()
    {
        return $this->hasOne(Datums::className(), ['id' => 'datum_id']);
    }

    public function behaviors()
    {
        return [
            [
                'class' => FileUploadBehavior::className(),
                'attribute' => 'file',
                'filePath' => '@webroot/uploads/[[attribute_datum_id]]/[[id]].[[extension]]',
                'fileUrl' => '@app/web/uploads/[[attribute_datum_id]]/[[id]].[[extension]]',
            ],
            [
                'class' => SaveRelationsBehavior::className(),
                'relations' => ['fileSettings'],
            ],
        ];
    }

    /**
     * @return array
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function checkRequired()
    {
        $settings = $this->fileSettings;
        if (isset($settings[0])){
            $setting = $this->fileSettings[0];
        } else {
            return false;
        }

        if (!$setting) {
            Yii::$app->session->setFlash('error', "Settings for file {$this->file} not found.<br>".
                Html::a('Add', Url::to(['files/update', 'id' => $this->id])));
            return false;
        }

        if (!$setting->validate())
            return false;

        return true;
    }
}
