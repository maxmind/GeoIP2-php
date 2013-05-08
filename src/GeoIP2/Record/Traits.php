<?php

namespace GeoIP2\Record;

class Traits extends AbstractRecord
{
  protected $validAttributes = Array('autonomous_system_number',
                                     'autonomous_system_organization',
                                     'domain',
                                     'is_anonymous_proxy',
                                     'is_satellite_provider',
                                     'isp',
                                     'ip_address',
                                     'organization',
                                     'user_type');

}