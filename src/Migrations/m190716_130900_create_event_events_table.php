<?php

use yii\db\Migration;


/**
 * ------------------------------------------------------------
 * Таблица мероприятий `{{%event_events}}`.
 * -------------------------------------------------------------
 */
class m190716_130900_create_event_events_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%event_events}}', [
            'id' => $this->primaryKey(),
            'poster' => $this->string(100)->comment('Афиша'),
            'name' => $this->string(255)->notNull()->comment('Название'),
            'category_id' => $this->smallInteger()->notNull()->comment('Категория'),
            'date_from' => $this->date()->notNull()->comment('Дата начала'),
            'date_to' => $this->date()->notNull()->comment('Дата окончания'),
            'status' => $this->string(20)->notNull()->comment('Статус'),
            'cancel_reason' => $this->string(255)->comment('Причина отмены'),
            'contacts_json' => $this->json(),
            'urls_json' => $this->json(),
            'description' => $this->text(),
            'view_count' => $this->integer()->notNull()->defaultValue(0)->comment('Колличество просмотров'),
            'owner_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->createIndex('{{%idx-event_events-name}}', '{{%event_events}}', 'name');
        $this->createIndex('{{%idx-event_events-category_id}}', '{{%event_events}}', 'category_id');
        $this->createIndex('{{%idx-event_events-status}}', '{{%event_events}}', 'status');
        $this->createIndex('{{%idx-event_events-owner_id}}', '{{%event_events}}', 'owner_id');
        $this->createIndex('{{%idx-event_events-view_count}}', '{{%event_events}}', 'view_count');

        $this->addForeignKey(
            '{{%fk-event_events-owner_id}}',
            '{{%event_events}}',
            'owner_id',
            '{{%users}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('{{%fk-event_events-owner_id}}', '{{%event_events}}');
        $this->dropTable('{{%event_events}}');
    }
}
