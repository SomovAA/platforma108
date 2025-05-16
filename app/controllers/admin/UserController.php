<?php

namespace app\controllers\admin;

use app\models\User;
use app\models\UserSearch;
use DateTime;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'create', 'update', 'delete'],
                            'allow' => true,
                            'matchCallback' => function ($rule, $action) {
                                return !Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin();
                            }
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id ID
     * @return string
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
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User();

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $dateTime = (new DateTime())->format(DateTime::ISO8601);
            $model->created_at = $dateTime;
            $model->updated_at = $dateTime;
            $password = $model->password_hash;
            $model->setPassword($password);
            $model->generateAuthKey();
            if($model->isWait()){
                $model->generateEmailConfirmToken();
            }
            if ($model->save()) {
                if($model->isWait()){
                    Yii::$app->mailer->compose('confirmEmail', ['user' => $model, 'password'=>$password])
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                        ->setTo($model->email)
                        ->setSubject('Подтверждение по электронной почте для ' . Yii::$app->name)
                        ->send();
                    Yii::$app->getSession()->setFlash('success', 'Пользователь создан и ему требуется подтвердить электронный адрес.');
                } else if($model->isActive()){
                    Yii::$app->mailer->compose('signupEmail', ['user' => $model,'password'=>$password])
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                        ->setTo($model->email)
                        ->setSubject('Регистрация на ' . Yii::$app->name)
                        ->send();
                    Yii::$app->getSession()->setFlash('success', 'Пользователь создан и активирован. На почту ему отправлено письмо с логином и паролем.');
                } else if($model->isBlocked()){
                    Yii::$app->mailer->compose('signupEmail', ['user' => $model,'password'=>$password])
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                        ->setTo($model->email)
                        ->setSubject('Регистрация на ' . Yii::$app->name)
                        ->send();
                    Yii::$app->getSession()->setFlash('success', 'Пользователь создан и заблокирован. На почту ему отправлено письмо с логином и паролем.');
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $dateTime = (new DateTime())->format(DateTime::ISO8601);
            $model->created_at = $dateTime;
            $model->updated_at = $dateTime;
            $password = $model->password_hash;
            $model->setPassword($password);
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
