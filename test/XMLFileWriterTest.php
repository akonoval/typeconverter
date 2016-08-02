<?php

require 'src/output/XMLFileWriter.php';

/**
 * Tests CSVFileReader class.
 *
 * @author Andrii Konoval
 */
class XMLFileWriterTest extends PHPUnit_Framework_testCase
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
    
    private $result = <<<'XML'
<?xml version="1.0"?>
<hotels>
  <value>
    <name>HILTON</name>
    <address>address</address>
    <stars>1</stars>
    <contact>test</contact>
    <phone>123</phone>
    <uri>the.com</uri>
  </value>
  <value>
    <name>Hilton</name>
    <address>address</address>
    <stars>2</stars>
    <contact>test</contact>
    <phone>321</phone>
    <uri>the.com</uri>
  </value>
</hotels>

XML;
    
    public function test__costruct()
    {
        $obj = new XMLFileWriter($this->data);
        $xmlData = $obj->getXmlData();
        $this->assertContains($this->result, $xmlData);
    }

}
