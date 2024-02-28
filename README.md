Locally:
In order to build the image run:
docker build -t kanban-backend-laravel .

in order to run the container run: 
docker run -d  --name kanban-backend-laravel -p  8000:8000  kanban-backend-laravel


Running database:
after installing docker run:
docker compose up

Deployment process on AWS EC2 Linux server with apache:

first run the following commands:
   - sudo apt-get update
   - sudo apt-get upgrade
   - sudo apt-get install php8.1-curl
   - install composer by following the tutorial: https://getcomposer.org/download/
   - run composer install at the application root folder
   - sudo apt-get install apache2
   - sudo apt-get install php libapache2-mod-php php-mysql
   - in order to avoid forbidden error run the following:
      sudo chown -R www-data:www-data folder_of_apache_projects

then configure apache as following:

1 - move file "quasar-kanban-api.conf" to '/etc/apache2/sites-available'
2 - run the command sudo a2ensite quasar-kanban-api.conf
3 - sudo a2enmod rewrite (to enable mod_rewrite)
2 - on '/etc/apache2/ports' add 'Listen 4444'
4 - on the aws console go to your instance page and check the security groups,
    click on edit inbound rules and add the port 4444 as custom tcp, allowing requests from anywhere/ipv4

finally run 'sudo systemctl restart apache2' and the application should be available at http:/server-ip:4444



