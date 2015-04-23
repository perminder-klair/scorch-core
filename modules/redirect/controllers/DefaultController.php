<?php

namespace scorchsoft\scorchcore\modules\redirect\controllers;

use Yii;
use scorchsoft\scorchcore\modules\redirect\models\search\RedirectSearch;
use scorchsoft\scorchcore\modules\redirect\models\Redirect;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * DefaultController implements the CRUD actions for Redirect model.
 */
class DefaultController extends Controller
{
    public $pageTitle = 'Redirects';
    public $pageIcon = 'fa fa-bars';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['admin', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['admin', 'create', 'update', 'delete'], // Define specific actions
                        'allow' => true, // Has access
                        'roles' => ['admin'], // '@' All logged in users / or your access role e.g. 'admin', 'user'
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Redirect models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RedirectSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Admin all Redirect models.
     * @return mixed
     */
    public function actionAdmin()
    {
        $this->layout = '@backend/views/layouts/main';

        $searchModel = new RedirectSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $controllerName = $this->getUniqueId();

        $getColumns = [
            ['class' => 'yii\grid\SerialColumn'],
            'old_url:url',
            'new_url:url',
            'update_time',
            'active:boolean',
            ['class' => 'backend\components\ActionColumn'],
        ];

        $meta['title'] = $this->pageTitle;
        $meta['description'] = 'List all Redirects';
        $meta['pageIcon'] = $this->pageIcon;

        return $this->render('admin', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'controllerName' => $controllerName,
            'meta' => $meta,
            'getColumns' => $getColumns,
        ]);
    }

    /**
     * Displays a single Redirect model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Redirects model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Redirect;

        if (isset($_GET['content_id'])) {
            $model->content_id = $_GET['content_id'];
        }

        if (isset($_GET['content_type'])) {
            $model->content_type = $_GET['content_type'];
        }

        if ($model->save(false)) {
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return false;
    }

    /**
     * Updates an existing Redirects model.
     * If update is successful, the browser will be redirected back to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->layout = '@backend/views/layouts/main';

        Url::remember();

        $model = $this->findModel($id);
        $controllerName = $this->getUniqueId();

        $meta['title'] = $this->pageTitle;
        $meta['description'] = 'Update Redirects';
        $meta['pageIcon'] = $this->pageIcon;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Redirects has been updated');
            if (!is_null(Url::previous('page'))) {
                return $this->redirect(Url::previous('page'));
            }

            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'meta' => $meta,
                'controllerName' => $controllerName,
            ]);
        }
    }

    /**
     * Deletes an existing Redirects model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Redirects has been deleted');

        if (!is_null(Url::previous('page'))) {
            return $this->redirect(Url::previous('page'));
        }
        return $this->redirect(['admin']);
    }

    /**
     * Finds the Redirects model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Redirect the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if ($id !== null && ($model = Redirect::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
