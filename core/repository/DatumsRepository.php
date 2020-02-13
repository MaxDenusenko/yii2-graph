<?php


namespace app\core\repository;


use app\core\models\Datums;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

class DatumsRepository
{
    /**
     * @param $key
     * @return Datums
     * @throws NotFoundHttpException
     */
    public static function get($key): Datums
    {

        if (is_numeric($key)) {
            if (($model = Datums::findOne($key)) !== null) {
                return $model;
            } else if (($model = Datums::find()->where(['code' => $key])->one()) !== null) {
                return $model;
            }
        } else {
            if (($model = Datums::find()->where(['code' => $key])->one()) !== null) {
                return $model;
            } else if (($model = Datums::findOne($key)) !== null) {
                return $model;
            }
        }

        throw new NotFoundHttpException('Not found');
    }

    /**
     * @param Datums $model
     */
    public static function save(Datums $model): void
    {
        if (!$model->save()) {
            throw new \RuntimeException('Saving error');
        }
    }

    /**
     * @param Datums $model
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public static function remove(Datums $model): void
    {
        if (!$model->delete()) {
            throw new \RuntimeException('Removing error');
        }
    }
}
