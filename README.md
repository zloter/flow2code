# API

## Setup
These are step required to run application

```
php artisan migrate:fresh 
php artisan storage:link
```
It's recommended to run 
```
php artisan db:seed
```

For testing purpose. Also, it provide sample genre & country category

## Endpoints

### GET/movies
 - show list of files

### POST/movies
 - add movie
 Data structure:
 ```
title:sample
genres:[1,2]
description:sample
country_id:4
image: file
```
All data is required. It should be send as multipart/form-data

### PUT/movies/id
 - update movie
 Data structure:
 ```
title:test
genres:[1,2]
description:Once upon a time
country_id:4
image: file
_method: put
```
You can send any of this data. As it is send as multipart/form-data it's send as a POST 
and then you need to put additional field _method, to make it work, as shown above.

### DELETE/movies/id
 - update movie
 
### GET/movies/data
 - Return data required to add & edit movies (list of countries and genres)
 
### GET/movies/search
 - Search through titles and description of movies
