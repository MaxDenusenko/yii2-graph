<?php


namespace app\controllers;


use app\core\models\Datums;
use app\core\repository\DatumsRepository;
use app\core\services\DatumServices;
use yii\web\Controller;

class GraphController extends Controller
{
    public $datumService;
    public $datumsRepository;

    public function __construct($id, $module, DatumServices $datumService, DatumsRepository $datumsRepository, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->layout = 'graph';
        $this->datumService = $datumService;
        $this->datumsRepository = $datumsRepository;
    }

    public function actionView($key)
    {
        $datum = $this->datumsRepository->get($key);

        if (!$this->datumService->checkRequiredData($key))
            return $this->redirect(['datum/view', 'key' => $datum->code]);

        $files = $this->datumService->getFileAr($key);

        return $this->render('view', [
            'files' => $files,
            'datum_code' => $key,
        ]);
    }
}
