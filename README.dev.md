Steps for releasing:

1. Update CHANGELOG.md with release and date.
2. Run `./dev-bin/release.sh`. This will build the phar, generate the docs,
   tag the release, push it to origin, and update the GH releases with the
   release notes and Phar.
