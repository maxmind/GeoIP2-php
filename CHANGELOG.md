CHANGELOG
=========

0.6.3 (2014-05-12)
------------------

* With the previous Phar builds, some users received `phar error: invalid url
  or non-existent phar` errors. The correct alias is now used for the Phar,
  and this should no longer be an issue.

0.6.2 (2014-05-08)
------------------

* The Phar build was broken with Guzzle 3.9.0+. This has been fixed.

0.6.1 (2014-05-01)
------------------

* This API now officially supports HHVM.
* The `maxmind-db/reader` dependency was updated to a version that does not
  require BC Math.
* The Composer compatibility autoload rules are now targeted more narrowly.
* A `box.json` file is included to build a Phar package.

0.6.0 (2014-02-19)
------------------

* This API is now licensed under the Apache License, Version 2.0.
* Model and record classes now implement `JsonSerializable`.
* `isset` now works with model and record classes.

0.5.0 (2013-10-21)
------------------

* Renamed $languages constructor parameters to $locales for both the Client
  and Reader classes.
* Documentation and code clean-up (Ben Morel).
* Added the interface `GeoIp2\ProviderInterface`, which is implemented by both
  `\GeoIp2\Database\Reader` and `\GeoIp2\WebService\Client`.

0.4.0 (2013-07-16)
------------------

* This is the first release with the GeoIP2 database reader. Please see the
  `README.md` file and the `\GeoIp2\Database\Reader` class.
* The general exception classes were replaced with specific exception classes
  representing particular types of errors, such as an authentication error.

0.3.0 (2013-07-12)
------------------

* In namespaces and class names, "GeoIP2" was renamed to "GeoIp2" to improve
  consistency.

0.2.1 (2013-06-10)
------------------

* First official beta release.
* Documentation updates and corrections.

0.2.0 (2013-05-29)
------------------

* `GenericException` was renamed to `GeoIP2Exception`.
* We now support more languages. The new languages are de, es, fr, and pt-BR.
* The REST API now returns a record with data about your account. There is
  a new `GeoIP\Records\MaxMind` class for this data.
* The `continentCode` attribute on `Continent` was renamed to `code`.
* Documentation updates.

0.1.1 (2013-05-14)
------------------

* Updated Guzzle version requirement.
* Fixed Composer example in README.md.


0.1.0 (2013-05-13)
------------------

* Initial release.
