<?php

namespace app\controllers;

use Yii;
use app\models\Reports;
use app\models\ReportsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ReportsController implements the CRUD actions for Reports model.
 */
class ReportsController extends Controller
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
     * Lists all Reports models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReportsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Reports model.
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
     * Creates a new Reports model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Reports();

        $post_data = Yii::$app->request->post();

        $esito_create = $model->load($post_data) && $model->save();

        if ($esito_create) {
          foreach($post_data as $post_input => $value)
            if (preg_match('/allergen_(\d)+_id/', $post_input) == 1) {
              $allergen_index = str_replace('allergen_', '', str_replace('_id', '', $post_input));
              $allergen_id = $post_data[$post_input];
              $allergen_value = $post_data['allergen_'.$allergen_index.'_value'];

              if($allergen_id != -1) {
                $check_allergen = Yii::$app->getDb()->createCommand("SELECT * FROM rows WHERE allergen_id = ". $allergen_id ." AND report_id = ". $model->id)->queryAll();

                if (count($check_allergen) == 0) {
                  $result = Yii::$app->getDb()->createCommand("INSERT INTO rows VALUES (". $allergen_id .", ". $model->id .", ". ($allergen_value ? floatval($allergen_value) : '') .")")->execute();

                  if($result == 0)
                    return $this->redirect(['update', 'id' => $model->id]);
                }
              }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Reports model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $post_data = Yii::$app->request->post();

        $esito_update = $model->load($post_data) && $model->save();

        if ($esito_update)
        {
            foreach($post_data as $post_input => $value)
            {
              if (preg_match('/old_allergen_(\d)+_id/', $post_input) == 1)
              {
                $allergen_index = str_replace('old_allergen_', '', str_replace('_id', '', $post_input));
                $allergen_id = $post_data[$post_input];
                $allergen_value = $post_data['old_allergen_'.$allergen_index.'_value'] ? $post_data['old_allergen_'.$allergen_index.'_value'] : '';

                if($allergen_value == '')
                {
                  $result = Yii::$app->getDb()->createCommand("DELETE
                                                               FROM rows
                                                               WHERE allergen_id = ". $allergen_id ."
                                                               AND report_id = ". $model->id)->execute();
                }
                else
                {
                  $result = Yii::$app->getDb()->createCommand("UPDATE rows
                                                               SET allergen_value = ". ($allergen_value ? floatval($allergen_value) : "") ."
                                                               WHERE allergen_id = ". $allergen_id ." AND report_id = ". $model->id)->execute();
                }
              }
            }

            foreach($post_data as $post_input => $value)
            {
              if ((preg_match('/allergen_(\d)+_id/', $post_input) == 1) &&
                  (preg_match('#^((?!old).)*$#', $post_input) == 1))
              {
                $allergen_index = str_replace('allergen_', '', str_replace('_id', '', $post_input));
                $allergen_id = $post_data[$post_input];
                $allergen_value = $post_data['allergen_'.$allergen_index.'_value'];

                if($allergen_id != -1)
                {
                  $check_allergen = Yii::$app->getDb()->createCommand("SELECT *
                                                                       FROM rows
                                                                       WHERE allergen_id = ". $allergen_id ."
                                                                       AND report_id = ". $model->id)->queryAll();

                  if (count($check_allergen) == 0)
                  {
                    $result = Yii::$app->getDb()->createCommand("INSERT INTO rows
                                                                 VALUES (". $allergen_id .", ".
                                                                 $model->id .", ".
                                                                 ($allergen_value ? floatval($allergen_value) : '') .")")->execute();

                    if($result == 0)
                    {
                      return $this->redirect(['update', 'id' => $model->id]);
                    }
                  }
                }
              }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }
        else
        {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Reports model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Reports model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reports the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reports::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
