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
     * Creates the tables needed for the Records used by the plugin
     *
     * @return boolean
     */
    protected function createTables(): bool
    {

        /**
         * If there is a value that I am going to look up often, I can create
         * an index.
         *
         * Ben Croker
         * https://craftquest.io/courses/in-depth-on-craft-plugin-development/9370
         * 4 minutes in
         *
         * Yii Docs:
         * https://www.yiiframework.com/doc/api/2.0/yii-db-migration#createIndex()-detail
         *
         *  Also, $this->>addForiegnKey
         * If the delete property is set to cascade, If the record the key
         *  points to is deleted, so will the row that has
         * the foriegn key if the "cascade" is enabled
         *
         *
         */

        if (!$this->db->tableExists('{{%tractionms_appreviews}}')) {
            $this->createTable('{{%tractionms_appreviews}}', [
                'id' => $this->primaryKey(),
                'overall' => $this->text(),
                'favTrophy' => $this->text(),
                'suggestions' => $this->text(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
            ]);

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