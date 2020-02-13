<?php

namespace app\core\models;

use app\core\models\Files;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\UploadedFile;

/**
 * This is the model class for table "datums".
 *
 * @property int $id
 * @property string $name
 * @property int $created_at
 * @property string $code
 *
 * @property Files[] $files
 */
class Datums extends ActiveRecord
{
    public function checkRequiredData()
    {
        if (!count($this->files)) {
            Yii::$app->session->setFlash('error', 'Files not found');
            return false;
        }

        foreach ($this->files as $file) {
            if (!$file->checkRequired())
                return false;
        }
        return true;
    }

    public function addFile(UploadedFile $file)
    {
        $files = $this->files;
        $files[] = new Files(['file' => $file]);
        $this->updateFiles($files);
    }

    public function updateFiles($files)
    {
        $this->files = $files;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%datums}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'only_string'],
            [['name', 'code'], 'string', 'max' => 255],
            [['code'], 'unique'],
            [['created_at'], 'safe']
        ];
    }

    public function only_string($attribute_name, $params)
    {
        if (is_numeric($this->$attribute_name) || !preg_match('@[A-z]@u',$this->$attribute_name)) {

            $this->addError($attribute_name, 'This field must contain letters');
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'code' => 'Code',
        ];
    }

    /**
     * Gets query for [[Files]].
     *
     * @return ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(Files::className(), ['datum_id' => 'id']);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => SaveRelationsBehavior::className(),
                'relations' => ['files'],
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'code',
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

    public function beforeDelete()
    {
        if ($file = $this->files) {
            Yii::$app->session->setFlash('warning', 'You cannot delete an item that has linked files.');
            return false;
        }

        return parent::beforeDelete();
    }
}
