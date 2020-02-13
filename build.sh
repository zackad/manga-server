#!/usr/bin/env sh

composer install --no-dev
composer dump-autoload --optimize

yarn build
zip -r manga-server.$(git describe --abbrev=0).zip static vendor index.php template.html.twig
