# TPE-Celse-Bustos-Parte-3
# Colaboradores: Lucas Celse, Jeremias Bustos

Creamos una biblioteca de peliculas donde el usuario invitado podra visualizar todas las peliculas o elegir una. El administrador podra hacer lo mismo pero con la extension de poder borrar, crear, y modificar cada una de ellas.

## Enpoints

- __GET__
	- __TPE-Celse-Bustos-Parte-3/api/movies__
		- Esta ruta mostrara todas las peliculas que tiene la base de datos, esta disponible para cualquier usuario.
	- __QueryParams__
      	- OrderBy: Se utiliza para ordenar las peliculas indicando un campo.
      		- Campos validos
	      	  - `title`		-> _TPE-Celse-Bustos-Parte-3/api/movies?orderBy=title_
	      	  - `producer` 		-> _TPE-Celse-Bustos-Parte-3/api/movies?orderBy=producer_
	      	  - `duration` 	-> _TPE-Celse-Bustos-Parte-3/api/movies?orderBy=duration_
	      	  - `punct_imdb` 		-> _TPE-Celse-Bustos-Parte-3/api/movies?orderBy=punct_imdb_
	      	  - `genre_id` 		-> _TPE-Celse-Bustos-Parte-3/api/movies?orderBy=genre_id_
      	  - Direccion: El ordenamiento puede ser direccionado.
      	  	- `ASC` __(default)__ ->  _TPE-Celse-Bustos-Parte-3/api/movies?orderBy=title&direction=ASC_
      	  	- `DESC` ->  _TPE-Celse-Bustos-Parte-3/api/movies?orderBy=title&direction=DESC_
  		- Filter: Este se usa para poder filtrar las peliculas indicando un campo y un valor.
      		- Campos validos
	      	  - `title`		-> _TPE-Celse-Bustos-Parte-3/api/movies?filter=title_
	      	  - `producer` 		-> _TPE-Celse-Bustos-Parte-3/api/movies?filter=producer_
	      	  - `duration` 	-> _TPE-Celse-Bustos-Parte-3/api/movies?filter=duration_
	      	  - `punct_imdb` 		-> _TPE-Celse-Bustos-Parte-3/api/movies?filter=punct_imdb_
	      	  - `genre_id` 		-> _TPE-Celse-Bustos-Parte-3/api/movies?filter=genre_id_
      	  - Value: Indicamos el valor por el que queremos que se filtre ejemplo:
      	  	-  _TPE-Celse-Bustos-Parte-3/api/movies?filter=title&value=Interestelar_
  		- Paginacion: Sirve para poder paginar todas las peliculas como deseemos.
      		- `page`		-> Numero de pagina.
      		- `limit`		-> Cantidad de peliculas que se van a visualizar.
        	-  _TPE-Celse-Bustos-Parte-3/api/movies?page=2&limite=3_
	- __Ver pelicula por ID__
		- `TPE-Celse-Bustos-Parte-3/api/movies/:id`
---
- __POST__
	- __TPE-Celse-Bustos-Parte-3/api/movies__
		- Inserta una nueva pelicula a traves de un JSON, esta disponible para el administrador.
	- __Campos necesarios__
		- `title`
		- `description`
		- `producer`
		- `duraction`
		- `punct_imdb`
		- `image_url`
		- `genre_id`
  - __Ejemplo__
    ```json
    {
		"title": "Looper",
		"description": "Looper está situada en un futuro cercano y consiste en un grupo de asesinos a sueldo llamados Loopers, que trabajan para el sindicato del crimen del futuro. Sus jefes envían a los “objetivos” atrás en el tiempo, y el trabajo del Looper es simplemente disparar y deshacerse del cuerpo.",
		"producer": "Rian Jhonson",
		"duration": "120 minutes",
		"punct_imdb": 7.4,
		"image_url": "https://i.ibb.co/bQngf2W/memento.jpg",
		"genre_id": "3"
    }
    ```
---
- __PUT__
	- __TPE-Celse-Bustos-Parte-3/api/movies/:id__
		- Modifica una pelicula a traves de un JSON colocando el ID en la ruta, esta disponible para el administrador.
	- __Campos necesarios__
		- `title`
		- `description`
		- `producer`
		- `duraction`
		- `punct_imdb`
		- `image_url`
		- `genre_id`
---
- __DELETE__
	- __TPE-Celse-Bustos-Parte-3/api/movies/:id__
		- Borra una pelicula colocando el id en la ruta, esta disponible para el administrador.
---
- __Autenticacion__
	- Para poder utilizar POST, PUT y DELETE, el usuario debe ser loguearse como administrador y debe autenticarse con un token.
	- Login:
		- User: webadmin
  		- Password: admin
	- Token:
 		- __TPE-Celse-Bustos-Parte-3/api/user/token__
  - Si las credenciales coinciden, se devolvera un token que servira para poder utilizar los servicios disponibles solamente para el administrador.
