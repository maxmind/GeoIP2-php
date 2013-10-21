# GeoIP2 PHP API #

## Beta Note ##

This is a beta release. The API may change before the first production
release, which will be numbered 2.0.0.

You may find information on the GeoIP2 beta release process on [our
website](http://www.maxmind.com/en/geoip2_beta).

## Description ##

This distribution provides an API for the [GeoIP2 web services]
(http://dev.maxmind.com/geoip/geoip2/web-services) and the [GeoLite2
databases](http://dev.maxmind.com/geoip/geoip2/geolite2/). The commercial
GeoIP2 databases have not yet been released as a downloadable product.

## Installation ##

### Define Your Dependencies ###

We recommend installing this package with [Composer](http://getcomposer.org/).
To do this, add `geoip2/geoip2` to your `composer.json` file.

```json
{
    "require": {
        "geoip2/geoip2": "0.5.*"
    }
}
```

### Install Composer ###

Run in your project root:

```
curl -s http://getcomposer.org/installer | php
```

### Install Dependencies ###

Run in your project root:

```
php composer.phar install
```

### Require Autoloader ###

You can autoload all dependencies by adding this to your code:
```
require 'vendor/autoload.php';
```

### Optional C Extension ###

The [MaxMind DB API](https://github.com/maxmind/MaxMind-DB-Reader-php)
includes an optional C extension that you may install to dramatically increase
the performance of lookups in GeoIP2 or GeoLite2 databases. To install, please
follow the instructions included with that API.

The extension has no effect on web-service lookups.

## Database Reader ##

### Usage ###

To use this API, you must create a new `\GeoIp2\Database\Reader` object with
the path to the database file as the first argument to the constructor. You
may then call the method corresponding to the database you are using.

If the lookup succeeds, the method call will return a model class for the
record in the database. This model in turn contains multiple container
classes for the different parts of the data such as the city in which the
IP address is located.

If the record is not found, a `\GeoIp2\Exception\AddressNotFoundException`
is returned. If the database is invalid or corrupt, a
`\MaxMind\Db\InvalidDatabaseException` will be thrown.

See the API documentation for more details.

### Example ###

```php
<?php
require_once 'vendor/autoload.php';
use GeoIp2\Database\Reader;

// This creates the Reader object, which should be reused across
// lookups.
$reader = new Reader('/usr/local/share/GeoIP/GeoIP2-City.mmdb');

// Replace "city" with the appropriate method for your database, e.g.,
// "country".
$record = $reader->city('128.101.101.101');

print($record->country->isoCode . "\n"); // 'US'
print($record->country->name . "\n"); // 'United States'
print($record->country->names['zh-CN'] . "\n"); // '美国'

print($record->mostSpecificSubdivision->name . "\n"); // 'Minnesota'
print($record->mostSpecificSubdivision->isoCode . "\n"); // 'MN'

print($record->city->name . "\n"); // 'Minneapolis'

print($record->postal->code . "\n"); // '55455'

print($record->location->latitude . "\n"); // 44.9733
print($record->location->longitude . "\n"); // -93.2323

```

## Web Service Client ##

### Usage ###

To use this API, you must create a new `\GeoIp2\WebService\Client`
object with your `$userId` and `$licenseKey`, then you call the method
corresponding to a specific end point, passing it the IP address you want to
look up.

If the request succeeds, the method call will return a model class for the end
point you called. This model in turn contains multiple record classes, each of
which represents part of the data returned by the web service.

If there is an error, a structured exception is thrown.

See the API documentation for more details.

### Example ###

```php
<?php
require_once 'vendor/autoload.php';
use GeoIp2\WebService\Client;

// This creates a Client object that can be reused across requests.
// Replace "42" with your user ID and "license_key" with your license
// key.
$client = new Client(42, 'abcdef123456');

// Replace "city" with the method corresponding to the web service that
// you are using, e.g., "country", "cityIspOrg", "omni".
$record = $client->city('128.101.101.101');

print($record->country->isoCode . "\n"); // 'US'
print($record->country->name . "\n"); // 'United States'
print($record->country->names['zh-CN'] . "\n"); // '美国'

print($record->mostSpecificSubdivision->name . "\n"); // 'Minnesota'
print($record->mostSpecificSubdivision->isoCode . "\n"); // 'MN'

print($record->city->name . "\n"); // 'Minneapolis'

print($record->postal->code . "\n"); // '55455'

print($record->location->latitude . "\n"); // 44.9733
print($record->location->longitude . "\n"); // -93.2323

```

### What data is returned? ###

While many of the end points return the same basic records, the attributes
which can be populated vary between end points. In addition, while an end
point may offer a particular piece of data, MaxMind does not always have every
piece of data for any given IP address.

Because of these factors, it is possible for any end point to return a record
where some or all of the attributes are unpopulated.

See the
[GeoIP2 web service docs](http://dev.maxmind.com/geoip/geoip2/web-services)
for details on what data each end point may return.

The only piece of data which is always returned is the `ipAddress`
attribute in the `GeoIp2\Record\Traits` record.

Every record class attribute has a corresponding predicate method so you can
check to see if the attribute is set.

## Integration with GeoNames ##

[GeoNames](http://www.geonames.org/) offers web services and downloadable
databases with data on geographical features around the world, including
populated places. They offer both free and paid premium data. Each
feature is unique identified by a `geonameId`, which is an integer.

Many of the records returned by the GeoIP2 web services and databases
include a `geonameId` property. This is the ID of a geographical feature
(city, region, country, etc.) in the GeoNames database.

Some of the data that MaxMind provides is also sourced from GeoNames. We
source things like place names, ISO codes, and other similar data from
the GeoNames premium data set.

## Reporting data problems ##

If the problem you find is that an IP address is incorrectly mapped,
please
[submit your correction to MaxMind](http://www.maxmind.com/en/correction).

If you find some other sort of mistake, like an incorrect spelling,
please check the [GeoNames site](http://www.geonames.org/) first. Once
you've searched for a place and found it on the GeoNames map view, there
are a number of links you can use to correct data ("move", "edit",
"alternate names", etc.). Once the correction is part of the GeoNames
data set, it will be automatically incorporated into future MaxMind
releases.

If you are a paying MaxMind customer and you're not sure where to submit
a correction, please
[contact MaxMind support](http://www.maxmind.com/en/support) for help.

## Other Support ##

Please report all issues with this code using the
[GitHub issue tracker](https://github.com/maxmind/GeoIP2-php/issues).

If you are having an issue with a MaxMind service that is not specific
to the client API, please see
[our support page](http://www.maxmind.com/en/support).

## Requirements  ##

This code requires PHP 5.3 or greater. Older versions of PHP are not
supported.

This library also relies on the [Guzzle HTTP client](http://guzzlephp.org/).

## Contributing ##

Patches and pull requests are encouraged. All code should follow the
PSR-2 style guidelines. Please include unit tests whenever possible.

## Versioning ##

The GeoIP2 PHP API uses [Semantic Versioning](http://semver.org/).

## Copyright and License ##

This software is Copyright (c) 2013 by MaxMind, Inc.

This is free software, licensed under the GNU Lesser General Public License
version 2.1 or later.
