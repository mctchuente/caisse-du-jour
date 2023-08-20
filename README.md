# Caisse du jour
Interface avec Laravel pour l'opération d'une caisse de jour qui enregistre des encaissements en billets, pièces et centimes et en fait le récapitulatif.

# Installation de l'application
Il s'agit d'un projet Laravel, voici la liste des commandes à saisir dans l'invite de commandes (avec les droits administrateur) à la racine du dossier contenant les sources du projet après le téléchargement de ce dernier:

- composer update

** Créer la base de données mysql

** Renommer le fichier .env.example en .env

** Renseigner dans le fichier .env les paramètres d'accès à la base de données créée plus haut à savoir "DB_HOST", "DB_PORT", "DB_DATABASE", "DB_USERNAME", "DB_PASSWORD"

- php artisan migrate

- npm install

- npm run build

- php artisan db:seed --class=CreateAdminUser

- php artisan serve

** Une fois tout ce qui a été décrit au dessus fait sur votre local, ouvrir le navigateur et saisir l'url "http://localhost:8000"

** Les accès par défaut sont:

** Login: admin@admin.com

** Mot de passe: demo123

## A propos de Laravel

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).