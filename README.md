# Manga Server

![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/zackad/manga-server/test.yaml?branch=master&label=action&logo=github&style=for-the-badge)
[![Codecov](https://img.shields.io/codecov/c/github/zackad/manga-server?style=for-the-badge&logo=codecov)](https://codecov.io/gh/zackad/manga-server)
[![MIT License](https://img.shields.io/github/license/zackad/manga-server?style=for-the-badge)](https://github.com/zackad/manga-server/blob/master/LICENSE)
[![GitHub release (latest SemVer)](https://img.shields.io/github/v/release/zackad/manga-server?style=for-the-badge&logo=github)](https://github.com/zackad/manga-server/releases/latest)

Web application to serve manga collection from your computer over the network.

> Note: This is _NOT_ a CMS (Content Management System)

## Requirements

**Runtime**
- PHP version 7.4 or newer

**Development**
- composer
- NodeJS
- yarn (replacement for npm)

## How to run

- Download zip file from [latest release](https://github.com/zackad/manga-server/releases)
- Extract
- Open `.env` file and change `MANGA_ROOT_DIRECTORY` value to your manga collection folder
```shell
# Please change to something like MANGA_ROOT_DIRECTORY=/data/manga
#
# Warning: If you pointing to sub directory, do NOT add trailing slash e.g
# valid: MANGA_ROOT_DIRECTORY=/data/manga
# invalid: MANGA_ROOT_DIRECTORY=/data/manga/
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
git clone https://github.com/zackad/manga-server
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
