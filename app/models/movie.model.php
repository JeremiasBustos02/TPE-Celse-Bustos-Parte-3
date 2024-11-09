<?php
require_once 'config.model.php';
class MovieModel
{
    private $db;

    function __construct()
    {
        $config = new ConfigModel();
        $this->db = $config->getDB();
    }

    public function getMovies($orderBy = false, $direccion = " ASC", $filter, $value, $page = 1, $limit = 10)
    {
        // Calculamos el offset en base a la página y el límite
        $offset = ($page - 1) * $limit;
        
        $sql = 'SELECT * FROM movies';
        $params = [];
        
        if ($orderBy) {
            switch ($orderBy) {
                case 'title':
                    $sql .= ' ORDER BY title';
                    break;
                case 'producer':
                    $sql .= ' ORDER BY producer';
                    break;
                case 'duration':
                    $sql .= ' ORDER BY duration';
                    break;
                case 'punct_imdb':
                    $sql .= ' ORDER BY punct_imdb';
                    break;
                case 'genre_id':
                    $sql .= ' ORDER BY genre_id';
                    break;
            }
            if ($direccion === 'DESC') {
                $sql .= ' DESC';
            }
        }

        if ($filter && $value) {
            switch ($filter) {
                case 'title':
                    $sql .= ' WHERE title LIKE :value';
                    break;
                case 'description':
                    $sql .= ' WHERE description LIKE :value';
                    break;
                case 'producer':
                    $sql .= ' WHERE producer LIKE :value';
                    break;
                case 'duration':
                    $sql .= ' WHERE duration LIKE :value';
                    break;
                case 'punct_imdb':
                    $sql .= ' WHERE punct_imdb LIKE :value';
                    break;
                case 'genre_id':
                    $sql .= ' WHERE genre_id LIKE :value';
                    break;
                default:
                    throw new Exception('filtro no válido');
            }
            $params[':value'] = '%' . $value . '%';
        }

        $sql .= ' LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset;

        $query = $this->db->prepare($sql);
        $query->execute($params);

        $movies = $query->fetchAll(PDO::FETCH_OBJ);
        if (empty($movies)) {
            return null; 
        }
        return $movies;
    }

    public function getMovie($id)
    {
        $query = $this->db->prepare('SELECT * FROM movies WHERE id = ?');
        $query->execute([$id]);

        $movie = $query->fetch(PDO::FETCH_OBJ);

        return $movie;
    }

    public function insertMovie($title, $description, $producer, $duration, $punct_imdb, $image_url, $genre_id)
    {
        $query = $this->db->prepare('INSERT INTO movies(title, description, producer, duration, punct_imdb, image_url, genre_id) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $query->execute([$title, $description, $producer, $duration, $punct_imdb, $image_url, $genre_id]);

        $id = $this->db->lastInsertId();

        return $id;
    }

    public function updateMovie($id, $title, $description, $producer, $duration, $punct_imdb, $image_url, $genre_id)
    {
        $query = $this->db->prepare('UPDATE movies SET title = ?, description = ?, producer = ?, duration = ?, punct_imdb = ?, image_url = ?, genre_id = ? WHERE id = ?');
        $query->execute([$title, $description, $producer, $duration, $punct_imdb, $image_url, $genre_id, $id]);
    }

    public function eraseMovie($id)
    {
        $query = $this->db->prepare('DELETE FROM movies WHERE id = ?');
        $query->execute([$id]);
    }
}