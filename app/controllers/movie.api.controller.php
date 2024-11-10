<?php
require_once './app/models/movie.model.php';
require_once './app/models/genre.model.php';
require_once './app/views/json.view.php';
class MovieApiController
{
    private $model;
    private $genreModel;
    private $view;

    public function __construct()
    {
        $this->model = new MovieModel();
        $this->genreModel = new GenreModel();
        $this->view = new JSONView();
    }

    // /api/movies
    public function getAll($req, $res)
    {
        $orderBy = false;
        $direction = null;
        if (isset($req->query->orderBy)) {
            $orderBy = $req->query->orderBy;
            if (isset($req->query->direction))
                $direction = $req->query->direction;
        }

        $filter = false;
        $value = false;
        if ((isset($req->query->filter)) && (isset($req->query->value))) {
            $filter = $req->query->filter;
            $value = $req->query->value;
        }

        $page = isset($req->query->page) ? (int) $req->query->page : 1;
        $limit = isset($req->query->limit) ? (int) $req->query->limit : 10;

        $movies = $this->model->getMovies($orderBy, $direction, $filter, $value, $page, $limit);

        if ($movies === null) {
            return $this->view->response(["No se encontraron resultados para el filtro $filter con valor $value"], 404);
        }

        return $this->view->response($movies);
    }

    // /api/movies/:id
    public function get($req, $res)
    {
        $id = $req->params->id;
        $movie = $this->model->getMovie($id);
        if (!$movie) {
            return $this->view->response("La pelicula con el id=$id no existe", 404);
        }
        return $this->view->response($movie);
    }

    // api/movies/:id (DELETE)
    public function delete($req, $res)
    {
        if (!$res->user) {
            return $this->view->response("No autorizado", 401);
        }
        $id = $req->params->id;
        $movie = $this->model->getMovie($id);
        if (!$movie) {
            return $this->view->response("La pelicula con el id=$id no existe", 404);
        }
        $this->model->eraseMovie($id);
        $this->view->response("La pelicula con el id=$id se eliminó con éxito");
    }

    // api/movies (POST)
    public function create($req, $res)
    {
        if (!$res->user) {
            return $this->view->response("No autorizado", 401);
        }
        if (
            empty($req->body->title) ||
            empty($req->body->description) ||
            empty($req->body->producer) ||
            empty($req->body->duration) ||
            empty($req->body->punct_imdb) ||
            empty($req->body->image_url) ||
            empty($req->body->genre_id)
        ) {
            return $this->view->response('Faltan completar datos', 400);
        }
        // Valido existencia del género
        $genreExists = $this->genreModel->getGenre($req->body->genre_id);
        if (!$genreExists) {
            $this->view->response("El género especificado no existe", 400);
            return;
        }

        $newMovie = $req->body;

        $id = $this->model->insertMovie(
            $newMovie->title,
            $newMovie->description,
            $newMovie->producer,
            $newMovie->duration,
            $newMovie->punct_imdb,
            $newMovie->image_url,
            $newMovie->genre_id
        );

        if (!$id) {
            return $this->view->response("Error al insertar pelicula", 500);
        }

        // Devolvemos la pelicula insertada
        $movie = $this->model->getMovie($id);
        return $this->view->response($movie, 201);
    }

    // api/movies/:id (PUT)
    public function update($req, $res)
    {
        if (!$res->user) {
            return $this->view->response("No autorizado", 401);
        }
        $id = $req->params->id;
        $movie = $this->model->getMovie($id);
        if (!$movie) {
            return $this->view->response("La pelicula con el id=$id no existe", 404);
        }

        // Validación de existencia del género
        $genreExists = $this->genreModel->getGenre($req->body->genre_id);
        if (!$genreExists) {
            $this->view->response("El género especificado no existe", 400);
            return;
        }

        // valido los datos
        if (
            empty($req->body->title) ||
            empty($req->body->description) ||
            empty($req->body->producer) ||
            empty($req->body->duration) ||
            empty($req->body->punct_imdb) ||
            empty($req->body->image_url) ||
            empty($req->body->genre_id)
        ) {
            return $this->view->response('Faltan completar datos', 400);
        }

        $newMovie = $req->body;

        $this->model->updateMovie(
            $id,
            $newMovie->title,
            $newMovie->description,
            $newMovie->producer,
            $newMovie->duration,
            $newMovie->punct_imdb,
            $newMovie->image_url,
            $newMovie->genre_id
        );

        $movie = $this->model->getMovie($id);
        $this->view->response($movie, 200);
    }
}
