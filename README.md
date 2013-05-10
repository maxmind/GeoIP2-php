# GeoIP2 PHP API #

## Description ##

Currently, this distribution provides an API for the GeoIP2 web services
 (as documented at http://dev.maxmind.com/geoip/precision).

In the future, this distribution will also provide the same API for the
GeoIP2 downloadable databases. These databases have not yet been
released as a downloadable product.

See GeoIP2::Webservice::Client for details on the web service client
API.

## Usage ##

The basic API for this class is the same for all of the web service end
points. First you create a web service client object with your MaxMind
``userId`` and ``licenseKey``, then you call the method corresponding to
a specific end point, passing it the IP address you want to look up.

If the request succeeds, the method call will return a model class for the end
point you called. This model in turn contains multiple record classes, each of
which represents part of the data returned by the web service.

## Example ##

    >>> use \GeoIP2\Webservice\Client;
    >>> $client = new Client(42, 'abcdef123456');
    >>> $omni = $client.omni('24.24.24.24');
    >>> $country = $omni.country;
    >>> echo $country.isoCode;

## Exceptions ##

For details on the possible errors returned by the web service itself, see
http://dev.maxmind.com/geoip2/geoip/web-services for the GeoIP2 web service
docs.

If the web service returns an explicit error document, this is thrown as a
```\GeoIP2\Exception\WebserviceException```. If some other sort of transport
error occurs, this is thrown as a ```\GeoIP2\Exception\HttpException```.
The difference is that the webservice error includes an error message and
error code delivered by the web service. The latter is thrown when some sort
of unanticipated error occurs, such as the web service returning a 500 or an
invalid error document.

If the web service returns any status code besides 200, 4xx, or 5xx, this also
becomes a ```\GeoIP2\Exception\HttpException```.

Finally, if the web service returns a 200 but the body is invalid, the client
throws a ```\GeoIP2\Exception\GenericException```.

## What data is returned? ##

While many of the end points return the same basic records, the attributes
which can be populated vary between end points. In addition, while an end
point may offer a particular piece of data, MaxMind does not always have every
piece of data for any given IP address.

Because of these factors, it is possible for any end point to return a record
where some or all of the attributes are unpopulated.

See http://dev.maxmind.com/geoip/precision for details on what data each end
point may return.

The only piece of data which is always returned is the :py:attr:`ip_address`
attribute in the :py:class:`geoip2.records.Traits` record.

Every record class attribute has a corresponding predicate method so you can
check to see if the attribute is set.

## Integration with GeoNames ##

GeoNames (http://www.geonames.org/) offers web services and downloadable
databases with data on geographical features around the world, including
populated places. They offer both free and paid premium data. Each
feature is unique identified by a ```geonameId```, which is an integer.

Many of the records returned by the GeoIP2 web services and databases
include a ```geonameId``` property. This is the ID of a geographical feature
(city, region, country, etc.) in the GeoNames database.

Some of the data that MaxMind provides is also sourced from GeoNames. We
source things like place names, ISO codes, and other similar data from
the GeoNames premium data set.

## Reporting data problems ##

If the problem you find is that an IP address is incorrectly mapped,
please submit your correction to MaxMind at
http://www.maxmind.com/en/correction.

If you find some other sort of mistake, like an incorrect spelling,
please check the GeoNames site (http://www.geonames.org/) first. Once
you've searched for a place and found it on the GeoNames map view, there
are a number of links you can use to correct data ("move", "edit",
"alternate names", etc.). Once the correction is part of the GeoNames
data set, it will be automatically incorporated into future MaxMind
releases.

If you are a paying MaxMind customer and you're not sure where to submit
a correction, please contact MaxMind support at
http://www.maxmind.com/en/support for help.

## Other Support ##

Please report all issues with this code using the GitHub issue tracker
at https://github.com/maxmind/GeoIP2-php/issues

If you are having an issue with a MaxMind service that is not specific
to the client API please see http://www.maxmind.com/en/support for
details.

## Requirements  ##

This code requires PHP 5.3 or greater. Older versions of PHP are not
supported.

This library also relies on the Guzze HTTP client, http://guzzlephp.org/

## Contributing ##

Patches and pull requests are encouraged. All code should follow the
PSR-2 style guidelines. Please include unit tests whenever possible.

## Author ##

Gregory Oschwald <goschwald@maxmind.com>

## Copyright and License ##

This software is Copyright (c) 2013 by MaxMind, Inc.

This is free software, licensed under the GNU Lesser General Public License
version 2.1 or later.

