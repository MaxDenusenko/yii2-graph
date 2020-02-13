<?php


namespace app\core\forms;


use app\core\models\FileSettings;
use yii\base\Model;

class FileSettingForm extends Model
{
    public $firstDataIndex;
    public $secondDataIndex;
    public $labelRowIndex;
    public $graphName;
    public $balance;
    public $skipTop;
    public $skipDown;
    public $maxElement;

    public function __construct(FileSettings $model = null, $config = [])
    {
        if ($model) {
            $this->firstDataIndex = $model->firstDataIndex;
            $this->secondDataIndex = $model->secondDataIndex;
            $this->labelRowIndex = $model->labelRowIndex;
            $this->graphName = $model->graphName;
            $this->balance = $model->balance;
            $this->skipTop = $model->skipTop;
            $this->skipDown = $model->skipDown;
            $this->maxElement = $model->maxElement;
        }

        parent::__construct($config);
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
        ];
    }
}
