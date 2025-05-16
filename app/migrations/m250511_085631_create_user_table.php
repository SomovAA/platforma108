<?php

use yii\db\Migration;

class m250511_085631_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'role' => $this->string()->notNull(),
            'status' => $this->string()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'auth_key' => $this->string(32)->null()->defaultValue(null),
            'email_confirm_token' => $this->string()->null()->defaultValue(null),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->null()->defaultValue(null),
        ]);

        $this->insert('user', [
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'status' => 'active',
            'created_at' => '2025-01-02 15:00:00',
            'updated_at' => '2025-01-02 15:00:00',
            'password_hash' => '$2y$13$Ly7NMVMSRYZkOxzTr4Mc3uejN7Qt1/917f8z8eGfPWH4xB3YK4QF.',
        ]);
        $this->insert('user', [
            'email' => 'manager@gmail.com',
            'role' => 'manager',
            'status' => 'active',
            'created_at' => '2025-01-02 15:00:00',
            'updated_at' => '2025-01-02 15:00:00',
            'password_hash' => '$2y$13$vGT4sgNrv6GPOM1inmF9DePo./sekmEfIViGshMzqwXeSVS2GVaWu',
        ]);
        $this->insert('user', [
            'email' => 'client@gmail.com',
            'role' => 'client',
            'status' => 'active',
            'created_at' => '2025-01-02 15:00:00',
            'updated_at' => '2025-01-02 15:00:00',
            'password_hash' => '$2y$13$ZKdTp36eLep1Dsn.6aQuZOGGtRVBhi5eMQwhYmeVNWBkBZ2FW7OEu',
        ]);
        $this->insert('user', [
            'email' => 'partner@gmail.com',
            'role' => 'partner',
            'status' => 'active',
            'created_at' => '2025-01-02 15:00:00',
            'updated_at' => '2025-01-02 15:00:00',
            'password_hash' => '$2y$13$OEtIRHGbyuqfmt4ywNhcPOv1Os2EXd8FyduELbmjHSsR4K0.wVlIC',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
