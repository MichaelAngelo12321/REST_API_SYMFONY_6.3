
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
Install composer:
```bash
  composer Install
  ```
To build the project using Docker, use the following command:
```bash
  docker-compose up --build
```
Migrate Entity:

```bash
  php bin/console doctrine:migrations:migrate
```



