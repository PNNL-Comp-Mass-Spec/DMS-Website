<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_accounts extends CI_Migration {

    function up()
    {
        if ( ! $this->db->table_exists('accounts'))
        {
            // Setup Keys
            $this->dbforge->add_key('id', true);

            $this->dbforge->add_field(array(
                'id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true),
                'company_name' => array('type' => 'VARCHAR', 'constraint' => '200', 'null' => false),
                'first_name' => array('type' => 'VARCHAR', 'constraint' => '200', 'null' => false),
                'last_name' => array('type' => 'VARCHAR', 'constraint' => '200', 'null' => false),
                'phone' => array('type' => 'TEXT', 'null' => false),
                'email' => array('type' => 'TEXT', 'null' => false),
                'address' => array('type' => 'TEXT', 'null' => false),
                'Last_Update' => array('type' => 'DATETIME', 'null' => false)
            ));

            $this->dbforge->add_field("Created_At TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
            $this->dbforge->create_table('accounts', true);
        }
    }

    function down()
    {
        $this->dbforge->drop_table('accounts');
    }
}
