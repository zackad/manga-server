{ pkgs, ... }:

{
  # https://devenv.sh/basics/
  env.GREET = "devenv";

  # https://devenv.sh/packages/
  packages = [
    pkgs.frankenphp
    pkgs.php82Extensions.xdebug
    pkgs.php82Packages.phive
    pkgs.nodejs-slim_18
    pkgs.yarn
    pkgs.zip
  ];

  # https://devenv.sh/scripts/
  scripts.hello.exec = "echo hello from $GREET";

  enterShell = ''
    hello
  '';

  # https://devenv.sh/languages/
  # languages.nix.enable = true;
  languages.php = {
    enable = true;
    package = pkgs.php82.buildEnv {
      # List of php extension required
      extensions = { all, enabled }: with all; enabled ++ [ pcov ];
    };
  };

  # https://devenv.sh/pre-commit-hooks/
  # pre-commit.hooks.shellcheck.enable = true;

  # https://devenv.sh/reference/options/#processimplementation
  # Select `honcho` as process manager
  process.implementation = "honcho";

  # https://devenv.sh/processes/
  processes.asset.exec = "yarn run dev";
  processes.web.exec = "frankenphp php-server --root public --listen :8000";

  # See full reference at https://devenv.sh/reference/options/
}
