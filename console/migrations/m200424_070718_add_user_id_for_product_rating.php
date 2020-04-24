<?php

use yii\db\Migration;

/**
 * Class m200424_070718_add_user_id_for_product_rating
 */
class m200424_070718_add_user_id_for_product_rating extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product_rating', 'user_id', $this->integer()->defaultValue(null));
        $this->addForeignKey('fk_product_rating_user_id', 'product_rating', 'user_id', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_product_rating_user_id', 'product_rating');
        $this->dropColumn('product_rating', 'user_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200424_070718_add_user_id_for_product_rating cannot be reverted.\n";

        return false;
    }
    */
}
