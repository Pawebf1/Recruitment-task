# Recruitment task in php using Symfony framework

It consists of the following:

* PHP: 8.1-fpm
* Nginx: stable-alpine
* MariaDB: 10.7
* phpMyAdmin: latest

# Description

Main page is at [localhost:80]()

All transport and cargo can be seen at [localhost/transport/list]() or at [localhost:8080]() in phpMyAdmin.
Documents uploaded in form are stored in storage/uploads/documents/ and can be downloaded
at [localhost/transport/list]()

## Realized

* Form
* Validation of date and files field
* Saving form in db
* Sending emails

## Installation

* Clone this repository on your local computer
* Run the `docker-compose up -d`.
* Inside container with php run `composer install` and `php bin/console doctrine:migrations:migrate`
* Visit localhost:80 for app
* Visit localhost:8080 for phpMyAdmin

```shell
git clone https://github.com/Pawebf1/Recruitment-task.git
cd Recruitment-task
docker-compose up -d
```

```shell
docker exec -it recruitment-task-php bash 
composer install 
php bin/console doctrine:migrations:migrate
```

## Screenshots

### Main page

![](screenshots/localhost.png)

### Adding more cargo

![](screenshots/adding_cargo.png)

### Success submit

![](screenshots/success_submit.png)

### View at /transport/list

![](screenshots/transport_list.png)

### View at /cargo/list?id

![](screenshots/cargo_list.png)

### View in phpMyAdmin

![](screenshots/phpmyadmin_transport.png)
![](screenshots/phpmyadmin_cargo.png)

### Email

![](screenshots/email.png)

### Validation of files and date

![](screenshots/validation_files_date.png)