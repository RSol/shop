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

$model->seo();

$this->registerJsFile(Yii::getAlias('@web/js/site.js'), [
    'depends' => [
        AppAsset::class
    ],
    'position' => View::POS_READY,
]);

?>



<div class="content only-backs clear">
    <div class="clear">
        <div class="breadcrumbs left">
            <ul>
                <li><a href="/">Главная</a></li><li><?= Html::a($model->category['title'], $model->category->getUrl()); ?></li><li><?= $model->title ?></li>
            </ul>
        </div>
        <a class="back-to right" onclick="javascript:window.history.back();">Вернуться</a>
    </div>

    <div class="item product-detail" data-id="172" data-href="<?= Url::to($model->getUrl()); ?>" data-image="<?=$model->image->getImageFileUrl('image') ?>" data-title="<?= $model->title ?>" data-price="195" data-is-lucky="False" data-price-half="" data-description="<?= strip_tags($model->description) ?>" data-weight="170" data-unique_code="701" data-is-active="true" data-recommendation-url="" data-disallow_discount="false">
        <div class="product-loader">
            <div class="loader-inner ball-scale-ripple-multiple">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <div class="item-img left">
            <div class="item-img-wrap">
                <?php if($model->image): ?>
                    <?= Html::img($model->image->getImageFileUrl('image'), [
                        'class' => 'main-image',
                        'alt' => $model->title,
                    ]) ?>
                <?php endif; ?>
            </div>
            <?php if(count($model->images) > 1){ ?>
            <div class="extra-images">
                <ul>
                <?php foreach($model->images as $image): ?>
                    <li data-image_full="" class="">
                        <a class="example-image-link" href="<?= $image->getImageFileUrl('image') ?>" data-lightbox="example-set" data-title="">
                            <?= Html::img($image->getThumbFileUrl('image', 'thumb'), ['alt' => '', "class"=>"food-image"]) ?>
                        </a>
                    </li>
                <?php endforeach; ?> 
                </ul>
            </div>
            <?php } ?>
            <div class="suggestions hidden-sm-down" style="display:none">
                <h3>Многим понравилось еще</h3>
                <ul class="list-product clear">
                    <li class="product" data-href="/menu/rolly/roll-taiskii/" data-id="839" data-image="<?=$model->image->getImageFileUrl('image') ?>" data-title="<?= $model->title ?>" data-is-lucky="False" data-price="285" data-price-half="143" data-description="<?= strip_tags($model->description) ?>" data-weight="230" data-unique_code="1324" data-is-active="true" data-recommendation-url="" data-disallow_discount="false">
                        <a href="<?= Url::to($model->getUrl()); ?>" class="product__capture">
                            <span class="product__img">
                                <?php if($model->image): ?>
                                    <?= Html::img($model->image->getImageFileUrl('image'), [
                                        'class' => 'main-image',
                                        'alt' => $model->title,
                                    ]) ?>
                                <?php endif; ?>
                                <br>
                            </span>
                            <p class="product__title" data-title=""><?= $model->title ?></p>
                        </a>
                        <div class="product__descr" style="display:none;"><?= $model->description ?></div>
                        <div class="product__weight" style="display:none;">230 г.</div>
                        <div class="product-buy">
                            <p class="product-price"><span><?= Yii::$app->formatter->asDecimal($model->price) ?></span> грн.</p>
                            <span class="icon icon-cart icon-fz22 add-to-basket add-product-to-basket"></span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="item-info right">
            <h1 class="add-product-to-basket-from-item" title="Добавить в корзину"><?= $model->title ?></h1>
            <p><?= $model->description ?></p>
            <i class="icon icon-like favor-no-add"><!-- Добавить в избранное -->&nbsp;</i>
            <div class="quantity quantity--teplye-rolly">
                <div class="clear">
                    <p class="lined lined--nah">
                        <b><span><?= Yii::$app->formatter->asDecimal($model->price) ?></span> грн</b>
                        <b style="display:none;">Старая цена: <span><?= Yii::$app->formatter->asDecimal($model->old_price) ?></span> грн.</b>
                        <!-- <br> -->
                        <span class="weight js-weight-hover"><?= $model->weight ?> г.</span>
                    </p>
                </div>
                <div class="quantity__ctrls">
                    <span class="counter">
                        <input type="text" name="counter" value="1">
                        <i class="ui-spinner-button ui-spinner-up plus"><span></span></i>
                        <i class="ui-spinner-button ui-spinner-down minus"><span></span></i>
                    </span>
                    <?= Html::a('В корзину', [
                        '/site/buy',
                        'id' => $model->id,
                        'amount' => 1,
                    ], [
                        'class' => 'button add-product-to-basket-from-item cart-link',
                        'data' => [
                            'url' => Url::to([
                                '/site/buy',
                                'id' => $model->id,
                            ]),
                        ]
                    ]) ?>
                </div>
            </div>
            <?= $this->render('_rating', ['model' => $model, 'disabled' => $disabled]); ?>
            <div class="social"></div>
        </div>
    </div>
</div>
