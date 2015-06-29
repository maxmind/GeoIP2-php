Steps for releasing:

1. Update CHANGELOG.md with release and date.
2. Run `./dev-bin/release.sh`. This will build the phar, generate the docs,
   tag the release, and push it to origin.
3. Visit the [GitHub Releases page](https://github.com/maxmind/GeoIP2-php/releases)
   and update the release with the change and upload the `geoip2.phar` file
   to the release.
