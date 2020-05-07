<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\search\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                    'delete' => ['POST'],
                    'restore' => ['POST'],
                    'force-delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProviderTrash = $searchModel->searchTrash(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderTrash' => $dataProviderTrash,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
            $model->status = $model->status == 1 ? 10 : 9;
            $model->username = $model->email;
            $model->generateAuthKey();
            $model->generateEmailVerificationToken();
            $model->setPassword($model->password_hash);

            if($model->save()) {
                Yii::$app->session->setFlash('success', 'User created.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $model->password_hash = '';

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->user->identity->id != $id) {
                $model->status = $model->status == 1 ? 10 : 9;
            } else {
                Yii::$app->session->setFlash('warning', 'You can not update status for your self');
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
            
            $model->username = $model->email;
            if (!empty($model->password_hash)) {
                $model->setPassword($model->password_hash);
            } else {
                $model->password_hash = (string) $model->getOldAttribute('password_hash');
            }
            
            if($model->save()) {
                Yii::$app->session->setFlash('success', 'User updated.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $model->status = $model->status == 10 ? 1 : 0;
        $model->password_hash = '';

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Restore an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRestore($id)
    {
        $this->findModel($id)->restore();
        Yii::$app->session->setFlash('success', 'User restore successfully.');
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing User model temporary.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->identity->id == $id) {
            Yii::$app->session->setFlash('warning', 'You can not delete your self');
            return $this->redirect(['index']);
        }
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'User deleted.');
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing User model permanently.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionForceDelete($id)
    {
        $this->findModel($id)->forceDelete();
        Yii::$app->session->setFlash('success', 'User delete permanently.');
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
