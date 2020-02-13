<?php

namespace app\controllers;

use app\core\forms\DatumFileForm;
use app\core\repository\DatumsRepository;
use app\core\services\DatumServices;
use Yii;
use app\core\models\Datums;
use app\models\forms\DatumsSearch;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DatumsController implements the CRUD actions for Datums model.
 */
class DatumsController extends Controller
{
    public $service;

    public function __construct($id, $module, DatumServices $service, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->service = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionGraph($key)
    {
        $datum = $this->findModel($key);

        if (!$this->service->checkRequiredData($key))
            return $this->redirect(['view', 'key' => $datum->code]);


        return $this->redirect(['graph/view', 'key' => $datum->code]);
    }

    /**
     * Lists all Datums models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DatumsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Datums model.
     * @param integer $key
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($key)
    {
        $datum = $this->findModel($key);
        $filesForm = new DatumFileForm();

        if ($filesForm->load(Yii::$app->request->post()) && $filesForm->validate()) {
            try {
                $this->service->addFiles($datum->code, $filesForm);
                Yii::$app->session->setFlash('success', 'Files have been added successfully. Next add file settings.');
                return $this->redirect(['view', 'key' => $datum->code]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', 'Saving error');
            }
        }

        $filesDataProvider = new ActiveDataProvider([
            'query' => $datum->getFiles(),
        ]);

        return $this->render('view', [
            'model' => $datum,
            'filesForm' => $filesForm,
            'filesDataProvider' => $filesDataProvider
        ]);
    }

    /**
     * Creates a new Datums model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Datums();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Datum element have been created successfully. Next download your html table.');
            return $this->redirect(['view', 'key' => $model->code]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Datums model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $key
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($key)
    {
        $model = $this->findModel($key);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Datum element have been updated successfully.');
            return $this->redirect(['view', 'key' => $model->code]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Datums model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $key
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($key)
    {
        if ($this->findModel($key)->delete())
            Yii::$app->session->setFlash('success', 'Datum element have been deleted successfully.');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Datums model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $key
     * @return array|ActiveRecord
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($key)
    {
        if($model = DatumsRepository::get($key))
            return  $model;

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
