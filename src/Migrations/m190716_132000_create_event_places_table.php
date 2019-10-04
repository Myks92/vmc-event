<?php

use yii\db\Migration;


/**
 * ------------------------------------------------------------
 * Таблица `{{%event_places}}`.
 * -------------------------------------------------------------
 */
class m190716_132000_create_event_places_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%event_places}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('Название'),
            'city_id' => $this->integer()->notNull()->comment('Город'),
            'street' => $this->string(255)->notNull()->comment('Улица'),
        ]);

        $this->createIndex('{{%idx-event_places-name}}', '{{%event_places}}', 'name');

        $this->addForeignKey(
            '{{%fk-event_places-city_id}}',
            '{{%event_places}}',
            'city_id',
            '{{%geo_city}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('{{%fk-event_places-city_id}}', '{{%event_places}}');
        $this->dropTable('{{%event_places}}');
    }
}
