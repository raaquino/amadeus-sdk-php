# Amadeus SDK for PHP - Version 1

The **Amadeus SDK for PHP** makes it easy for developers to access 
Amadeus Self-Service APIs in their PHP code, and build travel applications 
using services like Air (Flight search and Inteliigence), Hotel (Hotel search and offers) and 
Destination(Poinst of interest). You can get started in minutes by installing the SDK 
through Composer or by downloading a single zip or phar file from our latest release.

Inspired by the simple API wrapper provided by dahabtours GmbH 

## Features

* Provides easy-to-use HTTP clients for all supported Amadeus
  services and authentication protocol.
* Built on [Guzzle] and utilizes many of its features

## Getting Started
1. **Sign up for Amadeus** – Before you begin, you need to
   sign up for an Amadeus Developer Account and and set up your first application.
2. **Install the SDK** – Using [Composer] is the recommended way to install the
   Amadeus SDK for PHP.  in the base directory of your project to add the SDK as a dependency:
   ```
   composer require raaquino/amadeus-sdk-php:dev-master
   ```
   Please see the
   [Installation section of the User Guide][docs-installation] for more
   detailed information about installing the SDK through Composer and other
   means.

## Quick Examples

### Create an Amadeus Hotel client

```php
<?php
// Require the Composer autoloader.
require 'vendor/autoload.php';

use Amadeus\Hotel\HotelClient;

// Instantiate an Amadeus Hotel client.
$amadeus = new HotelClient(<client_id>,<client_secret>); 

$hotels = $amadeus->hotelOffer([
            'cityCode'        => 'LON'
]); 
```

### Create an Amadeus Flight client

```php
<?php
// Require the Composer autoloader.
require 'vendor/autoload.php';

use Amadeus\Air\AirClient;

// Instantiate an Amadeus Air client.
$amadeus = new AirClient(<client_id>,<client_secret>); 

$flights = $amadeus->lowFares([
            'origin'        => 'MAD',
            'destination' => 'PAR',
            'departureDate'=> '2019-08-01',
            'returnDate' => '2019-08-10'
]);  
```

### Create an Amadeus Destination client

```php
<?php
require 'vendor/autoload.php';
use Amadeus\Destination\DestinationClient;

// Instantiate an Amadeus Air client.
$amadeus = new DestinationClient(<client_id>,<client_secret>); 

$locations = $amadeus->pointOfInterest([
            'latitude'        => '41.397158',
            'longitude' => '2.160873',
            'radius'=> '2'
]); 

var_dump($locations);
  
?> 
```

### Related Amadeus Developer Projects

