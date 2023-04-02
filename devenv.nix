{ pkgs, ... }:

{
  # https://devenv.sh/basics/
  env.GREET = "devenv";

  # https://devenv.sh/packages/
  packages = [
    pkgs.php82Extensions.xdebug
    pkgs.nodejs-18_x
    pkgs.symfony-cli
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

  # https://devenv.sh/processes/
  processes.asset.exec = "npm run dev";
  processes.web.exec = "symfony server:start --no-tls";

  # See full reference at https://devenv.sh/reference/options/
}
