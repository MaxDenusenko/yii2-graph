<?php


namespace app\core\services;


use app\core\forms\EditFileForm;
use app\core\models\Files;

class FilesService
{

    public function edit(int $id, EditFileForm $form)
    {
        $file = Files::findOne($id);
        $file->setSetting($form->settings);
        $file->save();
    }
}
