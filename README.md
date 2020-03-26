# Manga Server

[![Build Status](https://travis-ci.org/zackad/manga-server.svg?branch=master)](https://travis-ci.org/zackad/manga-server)

Web application to serve manga collection from your computer over the network.

> Note: This is _NOT_ a CMS (Content Management System)

## Requirements

- PHP version 7.1.5 or newer (version 7.4 recommended)
- composer
- NodeJS
- yarn (replacement for npm)

## Development

- Clone this repository
```shell
git clone https://github.com/zaackad/manga-server
```
- Install dependencies
```shell
cd manga-server
composer install

yarn install
```
- Start the server
```shell
php -S 0.0.0.0:8000 public/index.php

# if you have symfony cli installed, you can use this command to start
# development server
symfony serve
```
- Watch the frontend compilation
```shell
yarn start
```
