#!/usr/bin/env sh

#git describe --exact-match > /dev/null 2>&1
#if [ $? != 0 ]; then
#  echo "[ERROR] Building a release artifact is for tagged commit only."
#  echo "exit: 1"
#  exit 1
#fi

# Prepare build target directory
rm -fr dist/manga-server
mkdir -p dist/manga-server

composer install --no-dev
composer dump-autoload --optimize

# Build frontend artifact
yarn build

# Copy artifact into dist directory
cp -r config public src templates vendor dist/manga-server

# Define environment variable
echo "APP_ENV=prod" > dist/manga-server/.env
echo "APP_SECRET=$(openssl rand -hex 20)" >> dist/manga-server/.env
echo "MANGA_ROOT_DIRECTORY=/" >> dist/manga-server/.env

#zip -r dist/manga-server.$(git describe --abbrev=0).zip static vendor index.php template.html.twig

# Re-install dependencies
composer install
