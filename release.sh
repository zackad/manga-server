#!/usr/bin/env sh

git describe --exact-match > /dev/null 2>&1
if [ $? != 0 ]; then
  echo "[ERROR] Building a release artifact is for tagged commit only."
  echo "exit: 1"
  exit 1
fi

composer install --no-dev
composer dump-autoload --optimize

yarn build
mkdir dist
zip -r dist/manga-server.$(git describe --abbrev=0).zip static vendor index.php template.html.twig
