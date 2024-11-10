<?php
require_once 'config.model.php';
class UserModel {
    private $db;

    function __construct() {
        $config = new ConfigModel();
        $this->db = $config->getDB(); // Obtenemos el objeto PDO
    }
 
    public function getUserByUsername($username) {    
        $query = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $query->execute([$username]);
    
        $user = $query->fetch(PDO::FETCH_OBJ);
    
        if ($user) {
            // Si existe usuario, retornamos el user
            return $user;
        } else {
            // Si no se encontro el user, retornamos null
            return null;
        }
    }
}