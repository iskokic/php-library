<?php
/**
* Website_Test
*
* @package      PHP Library
* @subpackage   phplibrary
* @category     Website
* @author       Zlatan Stajić <contact@zlatanstajic.com>
*/
use PHPUnit\Framework\TestCase as Test_Case;
use phplibrary\Website as website;

/**
* Testing Website class
*/
class Website_Test extends Test_Case {
    
    // -------------------------------------------------------------------------
    
    /**
    * Whether or not is possible to retrieve 
    * public properties from constructor
    * 
    */
    public function test_retrieving_public_properties()
    {
        $name        = 'PHP Library';
        $host        = 'http://localhost/_develop/php-library/';
        $made        = '2017';
        $language    = 'EN';
        $charset     = 'UTF-8';
        $description = 'PHP Library is set of classes containing most useful methods and variables for Web Development.';
        $keywords    = 'php, library, oop, php7';
        
        $website = new website(array(
            'name'        => $name,
            'host'        => $host,
            'made'        => $made,
            'language'    => $language,
            'charset'     => $charset,
            'description' => $description,
            'keywords'    => $keywords,
        ));
        
        $this->assertEquals($website->name, $name);
        $this->assertEquals($website->host, $host);
        $this->assertEquals($website->made, $made);
        $this->assertEquals($website->language, $language);
        $this->assertEquals($website->charset, $charset);
        $this->assertEquals($website->description, $description);
        $this->assertEquals($website->keywords, $keywords);
        
        $this->assertArrayHasKey('location', $website->server);
        $this->assertArrayHasKey('referer', $website->server);
        $this->assertArrayHasKey('host', $website->server);
        $this->assertArrayHasKey('uri', $website->server);
        $this->assertArrayHasKey('path', $website->server);
        $this->assertArrayHasKey('page', $website->server);
        
        $this->assertEquals($website->errors, array());
    }
    
    // -------------------------------------------------------------------------
}
