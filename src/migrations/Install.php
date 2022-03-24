<?php

namespace b4worldview\tractionms\migrations;

use Craft;
use craft\db\Migration;

class Install extends Migration
{

    /**
     * @return boolean
     */
    public function safeUp(): bool
    {
        if ($this->createTables()) {
            // $this->createIndexes();
            // $this->addForeignKeys();

            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
        }

        return true;
    }

    /**
     * @return boolean
     * @throws Throwable
     */
    public function safeDown(): bool
    {
        //$this->deleteElements();
        // $this->deleteFieldLayouts();
        $this->deleteTables();
        // $this->deleteProjectConfig();

        return true;
    }


    /**
     * Creates the tables needed for the Records used by the plugin
     *
     * @return boolean
     */
    protected function createTables(): bool
    {
        if (!$this->db->tableExists('{{%tractionms_appreviews}}')) {
            $this->createTable('{{%tractionms_appreviews}}', [
                'id' => $this->primaryKey(),
                'overall' => $this->text(),
                'favTrophy' => $this->text(),
                'suggestions' => $this->text(),
                'dateCreated' => $this->timestamp()->defaultExpression("CURRENT_TIMESTAMP"),
                'dateUpdated' => $this->timestamp()->defaultExpression("CURRENT_TIMESTAMP"),
                'uid' => $this->text(),
            ]);
        }
        return true;
    }

    /**
     * Delete tables
     *
     * @return void
     */
    protected function deleteTables()
    {
        // Drop tables with foreign keys first
        $this->dropTableIfExists('{{%tractionms_appreviews}}');
    }



}