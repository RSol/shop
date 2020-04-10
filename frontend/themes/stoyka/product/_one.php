<?php

use common\models\Product;
use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var $this View
 * @var $model Product
 */

$this->registerJsFile(Yii::getAlias('@web/js/site.js'), [
    'depends' => [
        AppAsset::class
    ],
    'position' => View::POS_READY,
]);
?>
<?php if($model->status == 1) { ?>
<li class="product with-accent lucky-product-False" data-href="<?= Url::to($model->getUrl()); ?>"
    data-id="<?= $model->id; ?>" data-title="<?= $model->title; ?>" data-is-lucky="False" data-price="<?= Yii::$app->formatter->asDecimal($model->price); ?>">
    <a href="<?= Url::to($model->getUrl()); ?>" class="product__capture">
        <span class="product__img">
            <?= Html::img($model->image ? $model->image->getThumbFileUrl('image', 'thumb') : 'DEFAULT IMAGE', [
                'alt' => $model->title,
                'title' => $model->title,
            ]) ?>
        </span>
        <p class="product__title" data-title="<?= $model->title; ?>"><?= $model->title; ?></p>
        <div class="product__info" data-toggle="popover" data-placement="bottom" data-content="<?= strip_tags($model->description) ?>"><?= $model->description; ?><?= $model->weight; ?> гр.</div>
    </a>
    <div class="product__descr" style="display:none;">
        <?= $model->description; ?>
    </div>
    <div class="product__weight" style="display:none;"><?= $model->weight; ?></div>
    <div class="product-buy">
        <p class="product-price"><span><?= Yii::$app->formatter->asDecimal($model->price); ?></span> грн.</p>
        <span class="counter">
            <input type="text" name="counter" value="1">
            <i class="ui-spinner-button ui-spinner-up plus"><span></span></i>
            <i class="ui-spinner-button ui-spinner-down minus"><span></span></i>
        </span>
        <?= Html::a('<span class="icon icon-cart icon-fz22 add-to-basket add-product-to-basket"></span>', [
                '/site/buy',
                'id' => $model->id,
                'amount' => 1,
            ],
            [
                'class' => 'cart-link',
                'data' => [
                    'url' => Url::to([
                        '/site/buy',
                        'id' => $model->id,
                    ]),
                ]
            ]
        ) ?>
    </div>
    <?php if($model->superprice == 1) { ?>
    <div class="label" style="background-color:#e76a6b">Суперцена</div>
    <?php } ?>
    <?php if($model->hits == 1) { ?>
    <div class="label" style="background-color:#FF9800"> Хит! </div>
    <?php } ?>
</li>
<?php } ?>
                      
