# Manga Server

![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/zackad/manga-server/test.yaml?branch=master&label=action&logo=github&style=for-the-badge)
[![Codecov](https://img.shields.io/codecov/c/github/zackad/manga-server?style=for-the-badge&logo=codecov)](https://codecov.io/gh/zackad/manga-server)
[![MIT License](https://img.shields.io/github/license/zackad/manga-server?style=for-the-badge)](https://github.com/zackad/manga-server/blob/master/LICENSE)
[![GitHub release (latest SemVer)](https://img.shields.io/github/v/release/zackad/manga-server?style=for-the-badge&logo=github)](https://github.com/zackad/manga-server/releases/latest)

Web application to serve manga collection from your computer over the network.

> Note: This is _NOT_ a CMS (Content Management System)

## Requirements

**Runtime**
- PHP version 8.1 or later with following extension enabled:
  - gd
  - zip

**Development**
- composer
- NodeJS
- yarn (replacement for npm)
- symfony-cli (optional)

## How to run

- Download zip file from the [latest release page](https://github.com/zackad/manga-server/releases/latest)
- Extract
- Open `.env` file and change `MANGA_ROOT_DIRECTORY` value to your manga collection folder. Alternatively you can copy `.env` file into `.env.local` to prevent your value to be overwritten when you update the app later.
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
symfony server:start
```
- Watch the frontend compilation
```shell
# Open another terminal session and run
yarn dev
```

- OPTIONAL: If you have [devenv](https://devenv.sh/getting-started/) setup on your machine, you can run `devenv up` to start all service required to start development.
