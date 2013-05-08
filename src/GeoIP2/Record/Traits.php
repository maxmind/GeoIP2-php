<?php

namespace GeoIP2\Record;

class Traits extends AbstractRecord
{
  protected $validAttributes = Array('autonomousSystemNumber',
                                     'autonomousSystemOrganization',
                                     'domain',
                                     'isAnonymousProxy',
                                     'isSatelliteProvider',
                                     'isp',
                                     'ipAddress',
                                     'organization',
                                     'userType');

}