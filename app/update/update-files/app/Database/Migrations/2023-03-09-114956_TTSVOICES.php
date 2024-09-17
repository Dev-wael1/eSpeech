<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TTSVOICES extends Migration
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
            'language' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',               
            ],
            'voice' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'display_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'gender' => [
                'type' => 'VARCHAR',
                'constraint' => '8',
                'null' => true,
                'default' => 'NULL'
            ],
            'provider' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'icon' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => '8',
                'default' =>'1'
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',

        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tts_voices');
    }

    public function down()
    {
        $this->forge->dropTable('tts_voices');
    }
}
