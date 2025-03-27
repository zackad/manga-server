{ pkgs, ... }:
let
  phpCustom = pkgs.php83.buildEnv {
    # List of php extension required
    extensions =
      { all, enabled }:
      with all;
      enabled
      ++ [
        imagick
        pcov
      ];
  };
in
{
  # https://devenv.sh/basics/
  env.OVERMIND_SKIP_ENV = true;

  # https://devenv.sh/packages/
  packages = [
    (pkgs.frankenphp.override { php = phpCustom; })
    pkgs.php83Extensions.xdebug
    pkgs.php83Packages.phive
    pkgs.nodejs-slim_18
    pkgs.yarn
    pkgs.zip
  ];

  # https://devenv.sh/languages/
  languages.php = {
    enable = true;
    package = phpCustom;
  };

  # https://devenv.sh/pre-commit-hooks/
  pre-commit.hooks = {
    nixfmt-rfc-style.enable = true;
    prettier = {
      enable = true;
      package = null; # use version managed by yarn
      entry = "node_modules/.bin/prettier --ignore-unknown --list-different --write";
    };
    php-cs-fixer = {
      enable = true;
      package = null; # use version managed by phive
      entry = "tools/php-cs-fixer --config=.php-cs-fixer.dist.php fix";
    };
    phpstan = {
      enable = true;
      package = null; # use version managed by composer
      entry = "vendor/bin/phpstan --memory-limit=-1 analyze";
      excludes = [
        "^config/secrets/.+"
        "^tests/.+"
        ".php-cs-fixer.dist.php"
        "deploy.dist.php"
      ];
    };

    # Custom hooks not provided by devenv
    twig-lint = {
      enable = true;
      entry = "bin/console lint:twig";
      types = [ "twig" ];
    };
  };

  # https://devenv.sh/reference/options/#processimplementation
  process.manager.implementation = "overmind";

  # https://devenv.sh/processes/
  processes.asset.exec = "yarn run dev";
  processes.web.exec = "frankenphp php-server --root public --listen :8000";

  # See full reference at https://devenv.sh/reference/options/
}
