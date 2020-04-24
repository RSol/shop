<?php

namespace frontend\controllers;

use common\models\Category;
use Yii;
use common\models\Product;
use common\models\ProductSearch;
use common\models\Rating;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex($id)
    {
        $category = $this->findCategory($id);

        $searchModel = new ProductSearch();
        $searchModel->category_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'category' => $category,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\db\Exception
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $user = Yii::$app->user->getId();
        $db = Yii::$app->db;
        $ip = Yii::$app->request->getUserIP();
        // - rating;
        $fetchProductRat = $db->createCommand("SELECT id FROM product_rating WHERE product_id='".$model->id."' AND user_id='".$user."'")->queryScalar();
        if($user == null || $fetchProductRat != null){
            $disabled = true;
        } else {
            $disabled = false;
        }

        $rating = Yii::$app->request->post('rating');
        if (isset($rating) && Rating::create($rating, $model->id, $user))
        {
            $fetchRatingCountSum = $db->createCommand("SELECT count(*), sum(rating) FROM product_rating WHERE product_id='".$model->id."'")->queryAll();
            $count = $fetchRatingCountSum[0]['count(*)'];
            $SummRating = $fetchRatingCountSum[0]['sum(rating)'];
            $itogRat = $SummRating/$count;

            $db->createCommand("UPDATE product SET rating='".$itogRat."' WHERE id='".$model->id."'")->execute();
            $this->refresh();
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'disabled' => $disabled,
        ]);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCategory($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
