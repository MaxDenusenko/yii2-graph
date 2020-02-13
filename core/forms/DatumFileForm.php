<?php


namespace app\core\forms;


use yii\base\Model;
use yii\web\UploadedFile;

class DatumFileForm extends Model
{
    public $files;

    public function rules(): array
    {
        return [
            [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'html', 'maxFiles' => 5],
        ];
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->files = UploadedFile::getInstances($this, 'files');
            return true;
        }
        return false;
    }
}
