<?php

namespace VPA\MediaLand\Models;

use VPA\DB\Mysql as DB;
use VPA\Config as Config;

class Users {
    /**
     * Object of DataBase class 
     *
     * @var Object
     */
    private $db;
    private $cfg;

    function __construct()
    {
        $this->cfg = new Config();
	$connection_data = $this->cfg->getSection('mysql');
        $this->db = new DB($connection_data);
    }

    /**
     * Select all tarifs for the service_id of the user {User_id}
     * @return array
     */
    public function getAuthUser(array $fields):array
    {
        $query = sprintf("SELECT `id`,`email`,`password`,`birthday`,`role`,`country` from `users` WHERE `email`='%s' AND `password`='%s' LIMIT 1",$this->db->escape($fields['email']),$this->db->escape($fields['password']));
        $result = $this->db->query($query);
        if ($result->num_rows == 0) {
            return [];
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC);
	return reset($rows);
    }

    public function addUser(array $fields):bool
    {
	unset($fields['action']);
	unset($fields['password2']);
	foreach ($fields as $key => $field) {
		$fields[$key] = $this->db->escape($field);
	}
	$query = sprintf("INSERT INTO users(`%s`) VALUE('%s')",implode("`,`",array_keys($fields)),implode("','",$fields));
        return $this->db->query($query);
    }
}
