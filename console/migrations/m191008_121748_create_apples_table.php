<?php

use yii\db\Migration;
use common\models\Apple;

/**
 * Handles the creation of table `{{%apples}}`.
 */
class m191008_121748_create_apples_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apples}}', [
            'id' => $this->primaryKey(),
            'color' => $this->string(50)->notNull(),
            'appearance_date' => $this->integer()->unsigned(),
            'fall_date' => $this->integer()->unsigned(),
            'status' => $this->smallInteger()->notNull()->defaultValue(Apple::STATUS_ON_TREE),
            'size' => $this->tinyInteger()->unsigned()->defaultValue(100),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%apples}}');
    }
}
