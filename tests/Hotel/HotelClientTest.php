<?php

use Amadeus\Hotel\HotelClient;

/**
 * @covers Amadeus\Hotel\HotelClient
 */
class HotelClientTest extends PHPUnit_Framework_TestCase
{
    
    public function testHotel()  
    {  
        $amadeus = new HotelClient("2U0AsGnClxEKQsGCOGHrgoi8fdXec8wR","gPpET3nmehGCMrzU"); 

        $hotels = $amadeus->hotelSearch([
            'cityCode'        => 'LON'
        ]); 

        var_dump($hotels);
       
    }
}
