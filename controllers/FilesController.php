<?php

namespace app\controllers;

use app\core\forms\EditFileForm;
use app\core\services\FilesService;
use Yii;
use app\core\models\Files;
use app\models\forms\FilesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FilesController implements the CRUD actions for Files model.
 */
class FilesController extends Controller
{
    public $service;

    public function __construct($id, $module, FilesService $service, $config = [])
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

    /**
     * Lists all Files models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FilesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Files model.
     * @param integer $key
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($key)
    {
        return $this->render('view', [
            'model' => $this->findModel($key),
        ]);
    }

    /**
     * Updates an existing Files model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $key
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($key)
    {
        $model = $this->findModel($key);
        $form = new EditFileForm($model);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            try {
                $this->service->edit($model->id, $form);
                Yii::$app->session->setFlash('success', 'File element have been updated successfully.');
                return $this->redirect(['view', 'key' => $model->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'form' => $form,
        ]);
    }

    /**
     * Deletes an existing Files model.
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
            Yii::$app->session->setFlash('success', 'File element have been deleted successfully.');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Files model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Files the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Files::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
