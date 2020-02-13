<?php

namespace app\core\models;

use app\core\models\Files;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "file_settings".
 *
 * @property int $id
 * @property int $file_id
 * @property int $firstDataIndex
 * @property int $secondDataIndex
 * @property int $labelRowIndex
 * @property string $graphName
 * @property float|null $balance
 * @property int|null $skipTop
 * @property int|null $skipDown
 * @property int|null $maxElement
 *
 * @property Files $file
 */
class FileSettings extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['firstDataIndex', 'secondDataIndex', 'labelRowIndex', 'graphName'], 'required'],
            [['firstDataIndex', 'secondDataIndex', 'labelRowIndex', 'skipTop', 'skipDown', 'maxElement'], 'integer'],
            [['balance'], 'number'],
            [['graphName'], 'string', 'max' => 255],
            [['file_id'], 'exist', 'skipOnError' => true, 'targetClass' => Files::className(), 'targetAttribute' => ['file_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_id' => 'File ID',
            'firstDataIndex' => 'First Data Index',
            'secondDataIndex' => 'Second Data Index',
            'labelRowIndex' => 'Label Row Index',
            'graphName' => 'Graph Name',
            'balance' => 'Balance',
            'skipTop' => 'Skip Top',
            'skipDown' => 'Skip Down',
            'maxElement' => 'Max Element',
        ];
    }

    /**
     * Gets query for [[File]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(Files::className(), ['id' => 'file_id']);
    }
}
