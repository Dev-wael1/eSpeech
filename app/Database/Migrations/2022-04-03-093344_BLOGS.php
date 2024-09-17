<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BLOGS extends Migration
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
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',               
            ],
            'description' => [
                'type' => 'LONGTEXT'
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'NULL' => true,
                'default' => 'NULL',
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => '4',
                'default' =>'1'
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',

        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('blogs');
    }

    public function down()
    {
        $this->forge->dropTable('blogs');
    }
}
