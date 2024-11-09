<?php
class GenreModel {
    private $db;

    public function __construct() {
        $config = new ConfigModel();
        $this->db = $config->getDB();
    }

    public function getGenre($id) {
        $query = $this->db->prepare('SELECT * FROM genres WHERE id = ?');
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }
}