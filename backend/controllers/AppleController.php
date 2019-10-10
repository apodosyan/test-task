<?php

namespace backend\controllers;

use Yii;
use common\models\Apple;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AppleController implements the CRUD actions for Apple model.
 */
class AppleController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete, remove-all' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Apple models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Apple::find()->orderBy(['id' => SORT_DESC]),
            'pagination' => false,

        ]);
        $statusLabels = Apple::statusLabels();
        return $this->render('index', compact('dataProvider', 'statusLabels'));
    }


    /**
     * Creates a new Apple model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionGenerate()
    {

        $applesCount = rand(1, 25);
        for ($i = 0; $i < $applesCount; $i++) {
            $apple = new Apple();
            $start = time();
            $end = $start + 3600 * 24 * 30;
            $apple->appearance_date = mt_rand($start, $end);
            $apple->color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));;
            $apple->save();
        }
        Yii::$app->session->setFlash('success', "Сгенерировано $applesCount новых яблок");

        return $this->redirect(['index']);
    }

    /**
     * Creates a new Apple model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionEatApple()
    {
        if (Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            $size = (int)Yii::$app->request->post('size');
            if ($size && $id) {
                $model = $this->findModel($id);
                if ($model->size >= $size && !in_array($model->status, [Apple::STATUS_ON_TREE, Apple::STATUS_ROTTEN])) {
                    $model->size -= $size;
                    if (!$model->size) {
                        $model->delete();
                        Yii::$app->session->setFlash('success', " Яблоко съедено полностью!");
                    } else {
                        Yii::$app->session->setFlash('success', "Осталось $model->size% ов яблока");
                        $model->save();
                    }
                }
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * Remove All .
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionRemoveAll()
    {
        Apple::deleteAll();
        return $this->redirect(['index']);
    }

    /**
     * Fall Apple.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionFall($id)
    {
        $model = $this->findModel($id);
        if (!$model->fall_date) {
            $model->fall_date = time();
            $model->status = Apple::STATUS_ON_GROUND;
            $model->save();
            Yii::$app->session->setFlash('success', "Яблоко упало.");
        } else {
            Yii::$app->session->setFlash('danger', "Яблоко уже упало.");
        }

        return $this->redirect(['index']);
    }


    /**
     * Finds the Apple model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Apple the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Apple::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
