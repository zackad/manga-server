# Manga Server

[![Build Status](https://travis-ci.org/zackad/manga-server.svg?branch=master)](https://travis-ci.org/zackad/manga-server)

Web application to serve manga collection from your computer over the network.

> Note: This is _NOT_ a CMS (Content Management System)

## Requirements

- PHP >= 7.1.5 (7.4 recommended)
- composer*
- NodeJS*
- yarn* (replacement for npm)

*: for development

## How to run

- Download zip file from [latest release](https://github.com/zackad/manga-server/releases)
- Extract
- Open `.env` file and change `MANGA_ROOT_DIRECTORY` value to your manga collection folder
```shell
# Please change to something like MANGA_ROOT_DIRECTORY=/data/manga
MANGA_ROOT_DIRECTORY=/
```
- Open terminal
- Navigate to extracted directory
- Run following command
```shell
php -S 0.0.0.0:8000 public/index.php
```
- Open web browser and access to `http://localhost:8000`
- If your computer is connected to local network, you can access it from other device (smartphone, tablet, or other computer) that connected to the same network by accessing to it's ip address e.g `http://192.168.100.12:8000`

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
