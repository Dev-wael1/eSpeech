<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class social_links extends Migration
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
            'site_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '512',
            ],
            'site_url' => [
                'type' => 'VARCHAR',
                'constraint' => '512',
            ],
            'site_icon' => [
                'type' => 'VARCHAR',
                'constraint' => '512',
                'null' => true,
            ],
            'site_html' => [
                'type' => 'VARCHAR',
                'constraint' => '2000',
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
        $this->forge->createTable('social_links');
    }

    public function down()
    {
        $this->forge->dropTable('social_links');
    }
}
