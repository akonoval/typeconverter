<?php

require 'src/output/JSONFileWriter.php';

/**
 * Tests JSONFileReader class.
 *
 * @author Andrii Konoval
 */
class JSONFileWriterTest extends PHPUnit_Framework_testCase
{
    private $data = array(
        [
            "name" => "HILTON",
            "address" => "address",
            "stars" => "1",
            "contact" => "test",
            "phone" => "123",
            "uri" => "the.com"
        ],
        [
            "name" => "Hilton",
            "address" => "address",
            "stars" => "2",
            "contact" => "test",
            "phone" => "321",
            "uri" => "the.com"
        ]
    );
    
    private $result = <<<'JSON'
{"name":"HILTON","address":"address","stars":"1","contact":"test","phone":"123","uri":"the.com"},{"name":"Hilton","address":"address","stars":"2","contact":"test","phone":"321","uri":"the.com"}
JSON;
    
    public function test__costruct()
    {
        $obj = new JSONFileWriter($this->data);
        $jsonData = $obj->getJsonData();
        $this->assertContains($this->result, $jsonData);
    }

}

