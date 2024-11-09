<?php
require_once './config.php';

class ConfigModel
{
  protected $db;

  public function __construct()
  {
    // Nos conectamos al servidos MySQL
    $this->db = new PDO(
      "mysql:host=" . MYSQL_HOST,
      MYSQL_USER,
      MYSQL_PASS
    );

    // Si no existe la base de datos, la creamos
    $this->db->exec("CREATE DATABASE IF NOT EXISTS " . MYSQL_DB .
      " CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

    // Ahora nos volvemos a conectar especificando la base de datos
    $this->db = new PDO(
      "mysql:host=" . MYSQL_HOST .
      ";dbname=" . MYSQL_DB . ";charset=utf8",
      MYSQL_USER,
      MYSQL_PASS
    );

    $this->__deploy();
  }

  public function getDB()
  {
    return $this->db;
  }

  private function __deploy()
  {
    //Acá se verifica si existen las tablas
    $query = $this->db->query('SHOW TABLES');
    $tables = $query->fetchAll();

    if (count($tables) == 0) {
      //Si no existen las tablas, creamos y llenamos con datos automaticamente
      $sql = <<<END
            CREATE TABLE `genres` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(20) NOT NULL,
              `image_url` varchar(255) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

            CREATE TABLE `movies` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(20) NOT NULL,
              `description` varchar(400) NOT NULL,
              `producer` varchar(20) NOT NULL,
              `duration` varchar(30) NOT NULL,
              `punct_imdb` double NOT NULL,
              `image_url` varchar(255) NOT NULL,
              `genre_id` int(11) NOT NULL,
              PRIMARY KEY (`id`),
              FOREIGN KEY (`genre_id`) REFERENCES `genres`(`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

            CREATE TABLE `users` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `username` varchar(50) NOT NULL,
              `password` varchar(255) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

            INSERT INTO `genres` (`id`, `name`, `image_url`) VALUES
            (1, 'Acción', 'https://i.ibb.co/4sbGV05/accion.jpg'),
            (2, 'Terror', 'https://i.ibb.co/Mhp6qPq/terror.jpg'),
            (3, 'Ciencia ficción', 'https://i.ibb.co/x8wfS81/cienciaficcion.jpg'),
            (4, 'Misterio', 'https://i.ibb.co/QkR2bXJ/misterio.jpg'),
            (5, 'Romance', 'https://i.ibb.co/d4Q5nyJ/romance.jpg');

            INSERT INTO `movies` (`id`, `title`, `description`, `producer`, `duration`, `punct_imdb`, `image_url`, `genre_id`) VALUES
            (1, 'Terminator', 'Un asesino cibernético...', 'James Cameron', '88 minutos', 8.1, 'https://i.ibb.co/HFq2j2d/terminator.jpg', 1),
            (2, 'El conjuro', 'A principios de los años 70...', 'James Wan', '91 minutos', 7.5, 'https://i.ibb.co/ysWt9k1/conjuro.jpg', 2),
            (3, 'Interestelar', 'Gracias a un descubrimiento...', 'Christopher Nolan', '120 minutos', 8.7, 'https://i.ibb.co/NVnpDRh/interstellar.jpg', 3),
            (4, 'Memento', 'Leonard, cuya memoria está dañada...', 'Christopher Nolan', '92 minutos', 6.6, 'https://i.ibb.co/bQngf2W/memento.jpg', 4),
            (5, 'Realmente amor', 'Las vidas de varias parejas...', 'Richard Curtis', '120 minutos', 7.6, 'https://i.ibb.co/B2wsDD9/realmenteamor.jpg', 5);

            INSERT INTO `users` (`id`, `username`, `password`) VALUES
            (1, 'webadmin', '$2y$10$1TvplMsRc9lE/jCAR9K2DONf6XiFqMyVL8Cju7jNTALvww763XvHa');
            END;

      $this->db->exec($sql);
    }
  }
}
