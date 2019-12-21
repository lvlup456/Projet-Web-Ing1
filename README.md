# Projet WEB

marche a suivre pour setup du projet:

Prerequis:
	avoir installer : php-mysql, php-gd

1/Installer les dependances:
	sudo php installer
	sudo php composer.phar install

2/Creer la BDD
	Verifier dans le .env que la ligne "DATABASE_URL:" utilise bien votre compte et mdp  mysql
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
	php bin/console app:create-user

3/Lancer le serveur en local:
php -S 127.0.0.1:8000 -t public/

4/Acceder au serveur, se rendre sur 127.0.0.1:8000

