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
  - imagick
  - zip

**Development**
- composer
- NodeJS
- yarn (replacement for npm)
- symfony-cli (optional)

## How to run

### Using Docker
> [!NOTE]
> To prevent accidental data lost, make sure to mount your media volume as readonly.

> [!IMPORTANT]
> By default, memory limit is 128MB and might not be enough to generage cover thumbnail (see #199).
> You can increase memory limit by setting `APP_MEMORY_LIMIT` env variable to the desired value.
```shell
docker run -d --publish 8000:8000 \
    --env APP_MEMORY_LIMIT=1G \
    --env MANGA_ROOT_DIRECTORY=/media \
    --volume /path/to/your/media:/media:ro \
    ghcr.io/zackad/manga-server:latest
```

### Manual
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

### Nix Support
This project support nix flakes with [direnv](https://direnv.net/) enabled.
```shell
# Enable direnv autoload
direnv allow

# Start development process
devenv up
```
