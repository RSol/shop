<?php

use common\models\ProductList;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model ProductList */


$this->registerJsFile(YII_DEBUG
    ? 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js'
    : 'https://cdn.jsdelivr.net/npm/vue');

$items = Json::encode($model->getProductListItems()->asArray()->all() ?: [
    [
        'id' => random_int(1, 100000),
        'title' => '',
        'sort' => 0,
        'price' => 0,
    ]
]);

$js = "function randomInteger(max) {
        // случайное число от min до (max+1)
        let rand = Math.random() * (max + 1);
        return Math.floor(rand);
    }

    const app = new Vue({
        el: '#product-items',
        data: {
            items: {$items}
        },
        methods: {
            addItem: function () {
                this.items.push({
                    id: randomInteger(100000),
                    title: '',
                    sort: 0,
                    price: 0
                });
            },
            rmItem: function (index) {
                this.items.splice(index, 1);
            }
        }
    })";

$this->registerJs($js);
?>


<div class="product-list-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'required')->checkbox() ?>


    <div class="panel panel-info">
        <div class="panel-heading">Состав</div>
        <div class="panel-body" id="product-items">
            <ul class="list-group">
                <li class="list-group-item" v-for="(item, index) in items" :key="item.id">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="sr-only">Заголовок</label>
                                <input class="form-control" placeholder="Название"
                                       :name="'items[' + item.id + '][title]'" :value="item.title">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="sr-only">Цена</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" placeholder="Цена"
                                           :name="'items[' + item.id + '][price]'" :value="item.price">
                                    <div class="input-group-addon">UAH</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="sr-only">Сортировка</label>
                                <input type="number" class="form-control" placeholder="Сортировка"
                                       :name="'items[' + item.id + '][sort]'" :value="item.sort">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <a href="javascript:;" class="btn btn-danger btn-sm" @click="rmItem(index)">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                            </a>
                        </div>
                    </div>

                </li>
            </ul>

            <a href="javascript:;" class="btn btn-success" @click="addItem">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Добавить
            </a>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>