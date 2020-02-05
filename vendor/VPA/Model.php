<?php

namespace VPA\Model;

use VPA\DB\Mysql as DB;

class Tarifs {
    /**
     * Object of DataBase class 
     *
     * @var Object
     */
    private $db;

    function __construct()
    {
    $this->db = new DB();
    }

    /**
     * Select all tarifs for the service_id of the user {User_id}
     * @return array
     */
    public function getTarifsForService(int $user_id,int $service_id):array
    {
        $query = sprintf("SELECT `ID`, `title`, `price`, `link`, `speed`, `pay_period`, `tarif_group_id` from `tarifs` WHERE `tarif_group_id`=%d ORDER BY `price`",$service_id);
        $result = $this->db->query($query);
        if ($result->num_rows == 0) {
            return ['result'=>'error'];
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $data = [
            'result'=>'ok',
        ];
        $title = $link = $speed = '';
        $tarifs = [];
        foreach ($rows as $r) {
            // Find shortest title
            if ($title == '' || strlen($title)>$r['title']) {
                $title = $r['title'];
                $link = $r['link'];
                $speed = $r['speed'];
            }
            $d = strtotime(date('d.m.Y 23:59:59'));
            $de = strtotime(sprintf("+%d months",$r['pay_period']),$d);
            
            $tarifs[] = [
                'ID' => $r['ID'],
                'title' => $r['title'],
                'price' => intval($r['price']),
                'new_payday' => sprintf("%d%s",$de,date('O')),
                'speed' => intval($r['speed']),
            ];
        }
        $data['tarifs'] = [
            'title' => $title,
            'link' => $link,
            'speed' => intval($speed),
            'tarifs' => $tarifs,
        ];
        return $data;
    }

    /**
     * Set a new tarif for the service_id of the user {User_id}
     * @return array
     */
    public function setTarifForService(int $user_id,int $service_id,int $tarif_id):array
    {
        // Record for Service and User exists ?
        $query = sprintf("SELECT `ID` FROM  `services` WHERE `id`=%d AND `user_id`=%d",$service_id,$user_id);
        $result = $this->db->query($query);
        if ($result->num_rows == 0) {
            return ['result'=>'error'];
        }
        $payday = date('Y-m-d');
        $query = sprintf("UPDATE `services` SET `tarif_id`='%s',`payday`='%s' WHERE `id`=%d AND `user_id`=%d",$tarif_id,$payday,$service_id,$user_id);
        return $this->db->query($query) ? ['result'=>'ok'] : ['result'=>'error'];
    }
}