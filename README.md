
# Simple Api For Symfony 6.3

The API allows for adding two numbers to the database, then it changes their order and updates the entry. Additionally, it supports retrieving results from the database with sorting and pagination options.

To run the project, you need to have Docker installed.

## Get Project
To download the project, use the following command:
```bash
  git clone https://github.com/MichaelAngelo12321/Rest_Api_Symfony_6.3.git
```
Then:

```bash
  cd .\Rest_Api_Symfony_6.3\
  ```
To build the project using Docker, use the following command:
```bash
  docker-compose up --build
```
Setting up Docker typically takes up to 10 minutes!

## API Reference

#### Create

Endpoint: `http://localhost:8080/exchange/values` </br>
HTTP Method: `POST`

Request Body:
```json
{
  "first": int,
  "second": int
}
```

Response:
```json

{
    "id": int,
    "firstOut": int,
    "secondOut": int,
    "updateAt": date
}

```

#### Get

Endpoint: `http://localhost:8080/exchange/history` </br>
HTTP Method: `POST`

Response:
```json

[
    {
        "id": int,
        "firstIn": int,
        "secondIn": int,
        "firstOut": int,
        "secondOut": int,
        "createdAt": date,
        "updateAt": date
    }
]

```

#### Get with pagging and sort

Example Endpoint: `http://localhost:8080/exchange/history?page=1&perPage=10&sortColumn=updateAt&sortOrder=desc` </br>
HTTP Method: `POST`

Response:
```json

[
    {
        "id": int,
        "firstIn": int,
        "secondIn": int,
        "firstOut": int,
        "secondOut": int,
        "createdAt": date,
        "updateAt": date
    }
]

```
