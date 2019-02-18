<?php

use yii\db\Migration;

class m180113_163313_create_table_option extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%option}}', [
            'id' => $this->integer(11)->notNull()->append('AUTO_INCREMENT PRIMARY KEY'),
            'name' => $this->string(64)->notNull(),
            'value' => $this->text()->notNull(),
            'label' => $this->string(64),
            'group' => $this->string(64),
        ], $tableOptions);

        $this->batchInsert('{{%option}}', ['name', 'value', 'label', 'group'], [
            ['sitetitle', 'Site title', 'Site Title', 'general'],
            ['tagline', 'Site description', 'Tagline', 'general'],
            ['admin_email', 'imanilchaudhari@gmail.com', 'E-mail Address', 'general'],
            ['allow_signup', '0', 'Membership', 'general'],
            ['default_role', 'subscriber', 'New User Default Role', 'general'],
            ['time_zone', 'Asia/Kathmandu', 'Timezone', 'general'],
            ['date_format', 'F d, Y', 'Date Format', 'general'],
            ['time_format', 'g:i:s a', 'Time Format', 'general']
        ]);

    }

    public function safeDown()
    {
        echo "m180113_163313_create_table_option cannot be reverted.\n";
        return false;
    }
}
