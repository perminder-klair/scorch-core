<?php

use yii\db\Schema;
use yii\db\Migration;

class m150116_143854_redirects extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%redirect}}', [
            'id' => Schema::TYPE_PK,
            'old_url' => Schema::TYPE_STRING . ' NOT NULL',
            'new_url' => Schema::TYPE_STRING,
            'create_time' => Schema::TYPE_TIMESTAMP . ' NULL DEFAULT 0',
            'update_time' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'active' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1',
            'deleted' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%redirect}}');
    }
}
