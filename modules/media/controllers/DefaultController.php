<?php

namespace scorchsoft\scorchcore\modules\media\controllers;

use scorchsoft\scorchcore\modules\media\models\Media;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use kato\modules\media\models\ContentMedia;
use kato\modules\media\controllers\DefaultController as Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use scorchsoft\scorchcore\modules\media\Media as MediaModule;
use scorchsoft\scorchcore\modules\media\models\MediaSearch;
use yii\grid\DataColumn;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    public $pageTitle = 'Media';
    public $pageIcon = 'fa fa-camera-retro fa-fw';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'update', 'delete', 'remove-image'],
                'rules' => [
                    [
                        'actions' => ['index', 'update', 'delete', 'remove-image'],
                        'allow' => true,
                        'roles' => ['admin', 'editor'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        Url::remember();

        $meta['title'] = $this->pageTitle;
        $meta['description'] = 'List all media';
        $meta['pageIcon'] = $this->pageIcon;

        $module = MediaModule::getInstance();
        if (!is_null($module->adminLayout)) {
            $this->layout = $module->adminLayout;
        }

        $searchModel = new MediaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        $getColumns = [
            [
                'label' => '',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::tag('a', $data->render([
                        'imgTag' => true,
                        'width' => 35,
                        'height' => 35,
                        'class' => 'img-responsive'
                    ]), ['href' => '/' . $data->source, 'target' => '_blank']);
                },
            ],
            [
                'attribute' => 'title',
                'format' => 'text',
                'label' => 'Title',
            ],
            'create_time',
            [
                'attribute' => 'statusLabel',
                'format' => 'text',
                'label' => 'Status',
            ],
            [
                'label' => 'Tags',
                'format' => 'html',
                'value' => function ($data) {
                  return $data->tagFilter();
                },
            ],
            [
                'label' => '',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a('Update | Delete', ['/media/default/update', 'id' => $data->id], [
                        'title' => 'Update | Delete',
                        'data-pjax' => '0',
                        'class' => 'btn btn-primary btn-xs',
                    ]);
                },
            ],
        ];

        return $this->render('index', [
            'meta' => $meta,
            'dataProvider' => $dataProvider,
            'getColumns' => $getColumns,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Updates an existing Media model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $module = MediaModule::getInstance();
        if (!is_null($module->adminLayout)) {
            $this->layout = $module->adminLayout;
        }

        $model = $this->findModel($id);

        //$model->title = $model->id;
        $controllerName = $this->getUniqueId();

        $meta['title'] = $this->pageTitle;
        $meta['description'] = 'Update media';
        $meta['pageIcon'] = $this->pageIcon;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Media has been updated');
            return $this->redirect(Url::previous());
        } else {
            return $this->render('update', [
                'model' => $model,
                'meta' => $meta,
                'controllerName' => $controllerName,
            ]);
        }
    }

    /**
     * Update data information of media
     * @param $id
     * @throws NotFoundHttpException
     */
    public function actionUpdateData($id)
    {
        if ($post = Yii::$app->request->post()) {
            $model = $this->findModel($id);
            if (!isset($post['name'])) {
                foreach ($post as $key => $val) {
                    $model->$key = $val;
                }
            } else {
                $model->$post['name'] = $post['value'];
            }
            if ($model->save(false)) {
                echo 'true';
                exit;
            }
        }
        echo 'false';
    }

    public function actionAssign()
    {
        if (isset($_GET['content_id']) && isset($_GET['content_type']) && isset($_GET['media_id'])) {
            $contentMedia = new ContentMedia();
            $contentMedia->content_id = $_GET['content_id'];
            $contentMedia->content_type = $_GET['content_type'];
            $contentMedia->media_id = $_GET['media_id'];
            if ($contentMedia->save()) {
                //success
                return true;
            } else {
                return false;
            }
        }

        return false;
    }


    public function actionRemoveImage()
    {
        if (isset($_GET['content_id']) && isset($_GET['content_type']) && isset($_GET['media_id'])) {
            //get for modal
            $images = ContentMedia::deleteAll(['content_id' => $_GET['content_id'], 'content_type' => $_GET['content_type'], 'media_id' => $_GET['media_id']]);

            $i = 0;
            foreach ($images as $image) {
                if ($image->delete()) {
                    $i++;
                }
            }

            //wtf is this?
            if ($i > 0){
                return true;
            }

        }
        return false;

    }

    public function actionMediaSearch()
    {
        if (isset($_GET['search_text'])) {

            $data = [];
            if ($mediaList = Media::find()->andWhere(['like', 'title', $_GET['search_text']])->limit(30)->all()) {
                foreach ($mediaList as $media) {
                    $data[] = [
                        'image_link' => $media->renderImage(['width' => 90, 'height' => 90,]),
                        'image_title' => $media->title,
                        'image_id' => $media->id,
                    ];

                }

                return json_encode($data);
            }
        }

        return false;
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        if (Yii::$app->request->isAjax) {
            echo 'true';
            exit;
        }

        Yii::$app->session->setFlash('success', 'Media has been deleted');

        return $this->redirect('/media/default/index');
    }

    /**
     * Finds the Media model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Media the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Media::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
