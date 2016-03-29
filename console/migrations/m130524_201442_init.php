<?php

use yii\db\Migration;

class m130524_201442_init extends Migration {

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('country_resource', [
            'id' => $this->integer(),
            'is_complete' => $this->boolean(FALSE)->notNull(),
            'coverage' => $this->text(),
            'authority' => $this->string(200),
            'authority_website' => $this->string(1000),
            'last_update' => $this->integer(),
                ], $tableOptions);

        //Either DATA LICENSE 
        $this->createTable('license_category', [
            'id' => $this->primaryKey(),
            'name' => $this->string(10),
                ], $tableOptions);

        $this->createTable('license', [
            'id' => $this->primaryKey(),
            'caption' => $this->text(),
            'country_id' => $this->integer(),
            'category_id' => $this->integer(),
                ], $tableOptions);

        $this->createTable('license_resource', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'caption' => $this->text(),
            'reference_website' => $this->string(1000),
            'licence_id' => $this->integer(),
            'category_id' => $this->integer(),
                ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }

}
