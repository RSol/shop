<?php

namespace common\models;

use frontend\components\JsonLDHelper;
use Throwable;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Rating;
use yz\shoppingcart\CartPositionInterface;
use yz\shoppingcart\CartPositionTrait;

/**
 * This is the model class for table "product".
 * @see https://github.com/omnilight/yii2-shopping-cart
 *
 * @property int $id
 * @property int|null $category_id
 * @property int $sort
 * @property string|null $title
 * @property string|null $description
 * @property int|null $price
 * @property int|null $status
 * @property int|null $sale
 * @property int|null $product_1c_id
 * @property int|null $old_price
 * @property int|null $weight
 * @property string|null $seoTitle
 * @property string|null $seoDescription
 * @property int|null $rating
 * @property int|null $update_utime
 *
 * @property Category $category
 * @property ProductImage[] $images
 * @property ProductImage $image
 * @property ProductList[] $lists
 * @property ProductListMap[] $productListMaps
 *
 * @mixin CartPositionTrait
 */
class Product extends ActiveRecord implements CartPositionInterface
{

    use CartPositionTrait;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const SALE_INACTIVE = 0;
    const SALE_ACTIVE = 1;

    public function getPrice()
    {
        return $this->price;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'price', 'old_price', 'weight', 'product_1c_id', 'sort', 'superprice', 'hits', 'status', 'sale'], 'integer'],
            ['update_utime', 'safe'],
            ['rating', 'number'],
            [['description', 'seoDescription'], 'string'],
            [['title', 'seoTitle'], 'string', 'max' => 255],
            [['sort'], 'default', 'value' => 0],
            [['update_utime'], 'default', 'value' => 0],
            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::class,
                'targetAttribute' => ['category_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Активный',
            'sale' => 'Акционный товар',
            'product_1c_id' => '1C ID',
            'category_id' => 'Категория',
            'sort' => 'Сортировка',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'price' => 'Цена',
            'weight' => 'Вес (в граммах)',
            'superprice' => 'Суперцена',
            'hits' => 'Хит',
            'old_price' => 'Старая цена',
            'seoTitle' => 'Seo Title',
            'seoDescription' => 'Seo Description',
        ];
    }

    public static function statusList()
    {
        return [
            self::STATUS_INACTIVE => 'Нет',
            self::STATUS_ACTIVE => 'Да',
        ];
    }

    public static function statusColorList()
    {
        return [
            self::STATUS_INACTIVE => 'danger',
            self::STATUS_ACTIVE => 'success',
        ];
    }

    public static function saleList()
    {
        return [
            self::SALE_INACTIVE => 'Нет',
            self::SALE_ACTIVE => 'Да',
        ];
    }

    public static function saleColorList()
    {
        return [
            self::SALE_INACTIVE => 'danger',
            self::SALE_ACTIVE => 'success',
        ];
    }

    /**
     * @param string $default
     * @param null $status
     * @return string
     */
    public function getStatusLabel($default = '-', $status = null)
    {
        return ArrayHelper::getValue(self::statusList(), $status ?: $this->status, $default);
    }

    /**
     * @param string $default
     * @param null $status
     * @return string
     */
    public function getStatusColor($default = 'default', $status = null)
    {
        return ArrayHelper::getValue(self::statusColorList(), $status ?: $this->status, $default);
    }

    /**
     * @param array $options
     * @return string
     */
    public function getStatusTag($options = [])
    {
        if (!array_key_exists('class', $options)) {
            $options['class'] = 'label label-' . $this->getStatusColor();
        }
        return Html::tag('span', $this->getStatusLabel(), $options);
    }

    /**
     * @param string $default
     * @param null $sale
     * @return string
     */
    public function getSaleLabel($default = '-', $sale = null)
    {
        return ArrayHelper::getValue(self::saleList(), $sale ?: $this->sale, $default);
    }

    /**
     * @param string $default
     * @param null $sale
     * @return string
     */
    public function getSaleColor($default = 'default', $sale = null)
    {
        return ArrayHelper::getValue(self::saleColorList(), $sale ?: $this->sale, $default);
    }

    /**
     * @param array $options
     * @return string
     */
    public function getSaleTag($options = [])
    {
        if (!array_key_exists('class', $options)) {
            $options['class'] = 'label label-' . $this->getSaleColor();
        }
        return Html::tag('span', $this->getSaleLabel(), $options);
    }

    /**
     * @return ActiveQuery
     */
    public function getProductListMaps()
    {
        return $this->hasMany(ProductListMap::class, ['product_id' => 'id']);
    }

    public function getProductsSale()
    {
        return Product::find()->where([
            'product.sale' => 1,
        ]);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[ProductImages]].
     *
     * @return ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(ProductImage::class, ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRating()
    {
        return $this->hasMany(Rating::class, ['product_id'=> 'id']);
    }

    /**
     * Gets query for [[ProductImages]].
     *
     * @return ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(ProductImage::class, ['product_id' => 'id'])->where([
            'product_image.main' => true
        ]);
    }

    public function getUrl()
    {
        return [
            '/product/view',
            'id' => $this->id,
        ];
    }

    /**
     * @see https://schema.org/Product
     */
    public function seo()
    {
        $view = Yii::$app->view;
        $view->title = $this->seoTitle;
        $view->registerMetaTag([
            'name' => 'description',
            'content' => $this->seoDescription,
        ]);

        $seo = [
            '@context' => 'http=>//schema.org',
            '@type' => 'Product',
            'name' => $this->title,
            'offers' => [
                '@type' => 'AggregateOffer',
                'price' => $this->price,
                'priceCurrency' => 'UAH'
            ]
        ];
        if ($this->image) {
            $seo['image'] = Url::to($this->image->getImageFileUrl('image'), true);
        }
        
        JsonLDHelper::add($seo);
    }

    public function afterSave($insert, $changedAttributes) 
    {
        if ($insert) {
            Rating::create($this->rating, $this->id);
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return bool
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function beforeDelete()
    {
        foreach ($this->images as $image) {
            $image->delete();
        }
        return parent::beforeDelete();
    }
}
