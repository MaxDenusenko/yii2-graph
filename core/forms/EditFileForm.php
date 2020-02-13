<?php


namespace app\core\forms;



use app\core\models\Files;

/**
 * Class EditFileForm
 * @package app\core\forms
 *
 * @property FileSettingForm $settings
 */
class EditFileForm extends CompositeForm
{
    public $file;

    public function __construct(Files $model = null, $config = [])
    {
        if ($model) {
            $this->file = $model->file;
            $settings = $model->fileSettings;
            if (isset($settings[0])){
                $this->settings = new FileSettingForm($settings[0]);
            } else {
                $this->settings = new FileSettingForm();
            }

        } else {
            $this->settings = new FileSettingForm();
        }

        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file'], 'required'],
            [['file'], 'string', 'max' => 255],
        ];
    }

    protected function internalForms(): array
    {
        return ['settings'];
    }
}
