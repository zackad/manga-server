#!/usr/bin/env sh

composer install --no-dev
composer dump-autoload --optimize

yarn build
mkdir dist
zip -r dist/manga-server.$(git describe --abbrev=0).zip static vendor index.php template.html.twig
