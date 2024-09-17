<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class REVIEWS extends Migration
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
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,        
                'default' => '0',
            ],
            'user_mail' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'user_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'user_image' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => 'NULL'
            ],
            'subject' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'review' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'rating_number' => [
                'type' => 'INT',
                'constraint' => 11,
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
        $this->forge->createTable('reviews');
    }

    public function down()
    {
        $this->forge->dropTable('reviews');
    }
}
