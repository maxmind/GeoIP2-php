Steps for releasing:

1. Review open issues and PRs to see if any can easily be fixed, closed, or
   merged.
2. Bump copyright year in `README.md`, if necessary.
3. Review `CHANGELOG.md` for completeness and correctness. Update its release
   date.
4. Install or update [hub](https://github.com/github/hub) as it used by the
   release script.
5. Run `./dev-bin/release.sh`. This will build the phar, generate the docs,
   tag the release, push it to origin, and update the GH releases with the
   release notes and Phar.
6. Verify the release on [GitHub](https://github.com/maxmind/GeoIP2-php/releases)
   and [Packagist](https://packagist.org/packages/geoip2/geoip2).
