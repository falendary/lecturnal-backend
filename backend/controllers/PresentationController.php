<?php

namespace backend\controllers;

use backend\helpers\AuthHelper;
use backend\models\PresentationPermission;
use Yii;
use backend\models\Presentation;
use backend\models\PresentationSearch;
use yii\data\ActiveDataProvider;
use yii\debug\components\search\matchers\Base;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PresentationController implements the CRUD actions for Presentation model.
 */
class PresentationController extends Controller
{

    public $modelClass = 'backend\models\Presentation';

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
     * Lists all Presentation models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (AuthHelper::isAuth()) {

            $user = AuthHelper::getUser();
            $presentationModel = new Presentation();
            $presentationPermissionModel = new PresentationPermission();

//            var_dump($user->id);

            $presentationQuery = $presentationPermissionModel->find();
            $presentationQuery->where(['user_id' => $user->id]);

            $presentationDataProvider = new ActiveDataProvider(array(
                'query' => $presentationQuery,
                'pagination' => false
            ));

            $presentations = $presentationDataProvider->getModels();
            $presentationIds = array();

            foreach ($presentations as $presentationItem) {
                array_push($presentationIds, $presentationItem->presentation_id);
            }

//            var_dump($presentationIds);


            $query = $presentationModel->find();
            $query->where(['id' => $presentationIds]);

            $dataProvider = new ActiveDataProvider(array(
                'query' => $query,
                'pagination' => false
            ));

            return $dataProvider->getModels();
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
    public function actionView($presentationId)
    {
//        echo '$presentationId '.$presentationId.' break';
//        echo '$slideId '.$slideId;

        $user = AuthHelper::getUser();

        if (PresentationPermission::find()->where(['user_id' => $user->id, 'presentation_id' => $presentationId])->one()) {
            return $this->findModel($presentationId);
        } else {
            return array("errors" => array("permission denied"));
        }
    }

    /**
     * Creates a new Presentation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        if (AuthHelper::isAuth()) {

            $request = Yii::$app->request;
            if ($request->isPost || $request->isAjax) {

                $request_body = $request->rawBody;
                $data = json_decode($request_body);
                $user = AuthHelper::getUser();

                $model = new Presentation();
                $model->created_at = date('Y-m-d H:m:s');
                $model->name = $data->name;
                $model->save();

                $presentationPermission = new PresentationPermission();

                $presentationPermission->presentation_id = $model->id;
                $presentationPermission->user_id = $user->id;
                $presentationPermission->save();

                return $model;
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
    public function actionUpdate($presentationId)
    {
        if (AuthHelper::isAuth()) {

            $request = Yii::$app->request;
            if ($request->isPut || $request->isAjax) {

                $user = AuthHelper::getUser();

                if (PresentationPermission::find()->where(['user_id' => $user->id, 'presentation_id' => $presentationId])->one()) {

                    $request_body = $request->rawBody;
                    $data = json_decode($request_body);

                    if ($presentation = Presentation::findOne(['id' => $presentationId])) {

                        $presentation->name = $data->name;
                        $presentation->save();

                        return $presentation;

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
     * Deletes an existing Presentation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $presentationId
     * @return mixed
     */
    public function actionDelete($presentationId)
    {

        $user = AuthHelper::getUser();

        if (PresentationPermission::find()->where(['user_id' => $user->id, 'presentation_id' => $presentationId])->one()) {
            $this->findModel($presentationId)->delete();
        } else {
            return array("errors" => array("permission denied"));
        }
    }

    /**
     * Finds the Presentation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Presentation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Presentation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
