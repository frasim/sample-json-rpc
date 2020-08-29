# Sample JSON-RPC Web Application

Sample web application used to retrieve the nearest pharmacies given a geo position.

Written in PHP, supported by Lumen Framework and a preconfigured Docker container

The application provides a JSON-RPC method named _SearchNearestPharmacy_ over HTTP.
The method accepts the following parameters:
- **currentLocation**, the location of the user, composed of:
  - **latitude**, float value indicating the current latitude of the user (e.g. 40.97153496)
  - **longitude**, float value indicating the current longitude of the user (e.g.
15.09370468)
- **range**, the maximum distance in meters (e.g. 5000)
- **limit**, the maximum number of entries to show (e.g. 2)

The response is a list of the nearest pharmacies including their name, position and distance, sorted by distance

During installation, the data of the pharmacies are imported from an external json file and stored in a database.


# Table of Contents
1. [Requirements](#requirements)
2. [Installation](#installation)
3. [Usage](#usage)
4. [Tests](#tests)

## Requirements

The application based on PHP7 and Lumen Framework, exposes, through API Rest, a service for the research of nearby pharmacies, present in a PostgreSQL relational database. The calculation of the distance between the geographic coordinates is carried out thanks to the Postgis extension.

The project is already prepared for the use of Docker (option A - see file docker-compose.yml, Dockerfile and the .docker folder). However, it can be executed locally by manually configuring the environment (option B).

### A) Docker
To start the application via container you need:
- Docker
- Docker Compose

### B) Local Environment
Setting up a local environment requires the installation of:
- Runtime PHP >= 7.2
- Webserver (Nginx, Apache or equivalent)
- PostgeSQL >= 10
- Postgis
- Composer

## Installation

### A) Docker
Located in the working directory

    $ cd <working directory>

#### 1. Checkout the repository
    
    $ git clone git@github.com:frasim/sample-json-rpc.git
    ----------------------------------------------------------------------    

#### 2. Build image

    $ docker-compose build app
    ----------------------------------------------------------------------
    Building app
    Step 1/11 : FROM php:7.4-fpm
     ---> 1e915dc40edc
    Step 2/11 : ARG user
     ---> Using cache
     ---> 4b7b6f683b9c
    Step 3/11 : ARG uid
     ---> Using cache
     ---> f5380d1f550e
    Step 4/11 : RUN apt-get update && apt-get install -y     libpng-dev     libonig-dev     libpq-dev     libxml2-dev     git     curl     zip     unzip     postgresql     postgresql-contrib
     ---> Using cache
     ---> 887811ae2d66
    Step 5/11 : RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
     ---> Using cache
     ---> 6c66afacc0c1
    Step 6/11 : RUN docker-php-ext-install pdo_pgsql exif pcntl bcmath gd
     ---> Using cache
     ---> b5e04391fcc4
    Step 7/11 : COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
     ---> Using cache
     ---> 85c7d818ae23
    Step 8/11 : RUN useradd -G www-data,root -u $uid -d /home/$user $user
     ---> Using cache
     ---> 47b8b143cc90
    Step 9/11 : RUN mkdir -p /home/$user/.composer &&     chown -R $user:$user /home/$user
     ---> Using cache
     ---> 82a0a487201c
    Step 10/11 : WORKDIR /var/www
     ---> Using cache
     ---> 49aee8203574
    Step 11/11 : USER $user
     ---> Using cache
     ---> 5435a8087947
    
    Successfully built 5435a8087947
    Successfully tagged app:latest

#### 3. Run services

    $ docker-compose up -d
    ----------------------------------------------------------------------
    Creating network "app-network" with driver "bridge"
    Creating application-webserver ... done
    Creating application           ... done
    Creating application-db        ... done

#### 4. Check services
    
    $ docker ps
    ----------------------------------------------------------------------
    CONTAINER ID        IMAGE                      COMMAND                  CREATED             STATUS              PORTS                                         NAMES
    390a083319ca        kartoza/postgis:11.5-2.8   "/bin/sh -c /scripts…"   4 minutes ago       Up 4 minutes        0.0.0.0:55432->5432/tcp                       application-db
    1646a810ea45        app                        "docker-php-entrypoi…"   4 minutes ago       Up 4 minutes        9000/tcp                                      application
    c7cd0a8ad504        nginx:alpine               "/docker-entrypoint.…"   4 minutes ago       Up 4 minutes        0.0.0.0:8000->80/tcp, 0.0.0.0:4443->443/tcp   application-webserver

#### 5. DB migrations
    
    $ docker-compose exec app php artisan migrate
    ----------------------------------------------------------------------
    Migration table created successfully.
    Migrating: 2020_08_27_234550_create_pharmacies_table
    Migrated:  2020_08_27_234550_create_pharmacies_table (0.08 seconds)
    Migrating: 2020_08_27_235534_import_pharmacies
    Migrated:  2020_08_27_235534_import_pharmacies (1.05 seconds)


### B) Local Environment


DB migrations
    
    $ php artisan migrate




## TESTS

    $ docker-compose exec app ./vendor/bin/phpunit



Stop all services

    $ docker stop application application-webserver application-db

    $ docker stop application

    $ docker stop application-webserver

    $ docker stop application-db


$ docker-compose exec db bash
