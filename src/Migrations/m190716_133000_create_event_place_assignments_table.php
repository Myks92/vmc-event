<?php

use yii\db\Migration;


/**
 * ------------------------------------------------------------
 * Таблица `{{%event_place_assignments}}`.
 * -------------------------------------------------------------
 */
class m190716_133000_create_event_place_assignments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%event_place_assignments}}', [
            'id' => $this->primaryKey(),
            'event_id' => $this->integer()->notNull()->comment('Мероприятие'),
            'place_id' => $this->integer()->notNull()->comment('Место'),
        ]);

        $this->createIndex(
            '{{%idx-event_place_assignments-event_place}}',
            '{{%event_place_assignments}}',
            ['event_id', 'place_id'],
            true
        );

        $this->addForeignKey(
            '{{%fk-event_place_assignments-place_id}}',
            '{{%event_place_assignments}}',
            'place_id',
            '{{%event_places}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-event_place_assignments-event_id}}',
            '{{%event_place_assignments}}',
            'event_id',
            '{{%event_events}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('{{%fk-event_place_assignments-place_id}}', '{{%event_place_assignments}}');
        $this->dropForeignKey('{{%fk-event_place_assignments-event_id}}', '{{%event_place_assignments}}');
        $this->dropTable('{{%event_place_assignments}}');
    }
}
