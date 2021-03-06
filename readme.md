[![PopBox](https://res.cloudinary.com/crunchbase-production/image/upload/v1439273613/ldo5vmwztskapyr7tpyn.png)](https://popbox.asia)

# RestAPI or Microservices Template

Already integrated with
  - JWT Auth
  - Valitron for Validation Library
  - DomPDF for PDF generation
  - RreadBeanPHP for ORM
  - Composer
  - Docker : Mysql and PHPMyAdmin

# Howto composer
  - Make sure you already installed PHP, MySQL, Composer
  - $ git clone [repo url] [folder name]
  - $ cd [folder name]/www
  - $ edit file config/config.ini to your preference
  - $ composer install 
  - $ composer run
  - open your browser and access to this url http://localhost:8080

# Howto Docker
- Make sure you already installed Docker
- $ git clone [repo url] [folder name]
- $ cd [folder name]
- $ docker-compose up --force-recreate --build
- open your browser to access the api url at http://localhost
- open your browser to access phpmyadmin at http://localhost:9090

# Scale the API
- $ docker-compose ps ---> show all running container
- $ docker-compose logs ---> show logs
- $ docker-compose scale www=3 ---> add more instance to www service

# Monitor
- $ docker container ls ---> list all running container
- $ docker stats [container name] ---> container stat
- $ docker stop rest-api-template_www_1  ---> stop a container
