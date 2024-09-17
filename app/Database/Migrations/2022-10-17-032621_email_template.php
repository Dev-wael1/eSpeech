<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class email_template extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'email_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '512',               
            ],
            'email_subject' => [
                'type' => 'VARCHAR',
                'constraint' => '512',
            ],
            'email_text' => [
                'type' => 'VARCHAR',
                'constraint' => '2000',
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => '1',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',                
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],

        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('email_template');
    }

    public function down()
    {
        $this->forge->dropTable('email_template');
    }
}
