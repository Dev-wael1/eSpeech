<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TTS_Languages extends Migration
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
            'language_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',               
            ],
            'language_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'flag' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => 'NULL'
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
        $this->forge->createTable('tts_languages');
    }

    public function down()
    {
        $this->forge->dropTable('tts_languages');
    }
}
