Zentlix Page Bundle
=================

This bundle is part of Zentlix CMS. Currently in development, please do not use in production!

## Установка
- Установить Zentlix CMS https://github.com/zentlix/MainBundle 
- Установить PageBundle:
```bash
    composer require zentlix/page-bundle
```
- Создать миграцию:
```bash 
    php bin/console doctrine:migrations:diff
```
- Выполнить миграцию: 
```bash 
    php bin/console doctrine:migrations:migrate
```
- Выполнить установку бандла:
```bash 
    php bin/console zentlix_main:install zentlix/page-bundle
```