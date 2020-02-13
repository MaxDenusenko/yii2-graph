<?php


namespace app\core\services;


use app\core\forms\DatumFileForm;
use app\core\models\Datums;
use app\core\repository\DatumsRepository;
use Yii;

class DatumServices
{
    private $repository;

    public function __construct(DatumsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function addFiles($key, DatumFileForm $filesForm)
    {
        $datum = $this->repository->get($key);
        foreach ($filesForm->files as $file) {
            $datum->addFile($file);
        }
        $datum->save();
    }

    public function checkRequiredData($key)
    {
        $datum = $this->repository->get($key);
        if (!$datum->checkRequiredData())
            return false;

        return true;
    }

    public function getFileAr($key)
    {
        $datum = $this->repository->get($key);
        $files = [];

        foreach ($datum->files as $file) {

            if ($settings = $file->fileSettings && isset($settings[0])){
                $setting = $file->fileSettings[0];
            } else {
                return false;
            }

            $files[$file->id]['file'] = $file->getUploadedFileUrl('file');
            $files[$file->id] = array_merge($files[$file->id], $setting);
        }

        return $files;
    }
}
