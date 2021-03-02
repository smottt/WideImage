{
  description = "An open-source PHP library for image manipulation";

  inputs = {
    # For old PHP versions.
    phps.url = "github:fossar/nix-phps";
  };

  outputs = { self, phps }:
    let
      # nixpkgs is a repository with software packages and some utilities.
      # From simplicity, we inherit it from the phps flake.
      inherit (phps.inputs) nixpkgs;

      # Configure the development shell here (e.g. for CI).

      # By default, we use the default PHP version from Nixpkgs.
      matrix.phpPackage = "php";
    in
      let
        # We only support a single platform at the moment,
        # since our binary cache only contains PHP packages for that.
        system = "x86_64-linux";

        # Get Nixpkgs packages for current platform.
        pkgs = nixpkgs.legacyPackages.${system};

        # Create a PHP package from the selected PHP package.
        php = phps.packages.${system}.${matrix.phpPackage};
      in {
        # Expose shell environment for development.
        devShell.${system} = pkgs.mkShell {
          nativeBuildInputs = [
            # Composer and PHP.
            php
            php.packages.composer
          ];
        };
      };
}
