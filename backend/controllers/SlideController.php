<?php

namespace backend\controllers;

use backend\helpers\AuthHelper;
use backend\models\PresentationPermission;
use Yii;
use backend\models\Slide;
use backend\models\SlideSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SlideController implements the CRUD actions for Slide model.
 */
class SlideController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all Slide models.
     * @param integer $presentationId
     * @return mixed
     */
    public function actionIndex($presentationId)
    {

        if (AuthHelper::isAuth()) {
            $slideModel = new Slide();

            $user = AuthHelper::getUser();

            if (PresentationPermission::find()->where(['user_id' => $user->id, 'presentation_id' => $presentationId])->one()) {
                $query = $slideModel->find();
                $query->where(["presentation_id" => $presentationId]);

                $dataProvider = new ActiveDataProvider(array(
                    'query' => $query,
                    'pagination' => false
                ));

                return $dataProvider->getModels();
            } else {
                return array("errors" => array("permission denied"));
            }
        } else {
            return array("errors" => array("permission denied"));
        }
    }

    /**
     * Displays a single Slide model.
     * @param integer $presentationId
     * @param integer $slideId
     * @return mixed
     */
    public function actionView($presentationId, $slideId)
    {
//        echo '$presentationId '.$presentationId.' break';
//        echo '$slideId '.$slideId;

        $user = AuthHelper::getUser();

        if (PresentationPermission::find()->where(['user_id' => $user->id, 'presentation_id' => $presentationId])->one()) {
            return $this->findModel($slideId);
        } else {
            return array("errors" => array("permission denied"));
        }
    }

    /**
     * Creates a new Slide model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $presentationId
     * @return mixed
     */
    public function actionCreate($presentationId)
    {
        if (AuthHelper::isAuth()) {

            $request = Yii::$app->request;
            if ($request->isPost || $request->isAjax) {

                $user = AuthHelper::getUser();

                if (PresentationPermission::find()->where(['user_id' => $user->id, 'presentation_id' => $presentationId])->one()) {
                    $request_body = $request->rawBody;
                    $data = json_decode($request_body);

                    $model = new Slide();
                    $model->created_at = date('Y-m-d H:m:s');
                    $model->presentation_id = $presentationId;
                    $model->content = $data->content;
                    $model->save();

                    return $model;
                } else {
                    return array("errors" => array("permission denied"));
                }
            } else {
                return array("errors" => array("method is not supported"));
            }

        } else {
            return array("errors" => array("permission denied"));
        }
    }

    /**
     * Updates an existing Slide model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $presentationId
     * @param integer $slideId
     * @return mixed
     */
    public function actionUpdate($presentationId, $slideId)
    {
        if (AuthHelper::isAuth()) {

            $request = Yii::$app->request;
            if ($request->isPut || $request->isAjax) {

                $user = AuthHelper::getUser();

                if (PresentationPermission::find()->where(['user_id' => $user->id, 'presentation_id' => $presentationId])->one()) {
                    $request_body = $request->rawBody;
                    $data = json_decode($request_body);

                    if ($slide = Slide::findOne(['id' => $slideId, 'presentation_id' => $presentationId])) {

                        $slide->content = $data->content;
                        $slide->save();

                        return $slide;

                    } else {
                        return array("errors" => array("slide not found"));
                    }
                } else {
                    return array("errors" => array("permission denied"));
                }
            } else {
                return array("errors" => array("method is not supported"));
            }

        } else {
            return array("errors" => array("permission denied"));
        }
    }

    /**
     * Deletes an existing Slide model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $presentationId
     * @param integer $slideId
     * @return mixed
     */
    public function actionDelete($presentationId, $slideId)
    {

        $user = AuthHelper::getUser();

        if (PresentationPermission::find()->where(['user_id' => $user->id, 'presentation_id' => $presentationId])->one()) {
            $this->findModel($slideId)->delete();
        } else {
            return array("errors" => array("permission denied"));
        }

    }

    /**
     * Finds the Slide model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Slide the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Slide::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
