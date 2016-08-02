<?php

require 'src/input/CSVFileReader.php';

/**
 * Tests CSVFileReader class.
 *
 * @author Andrii Konoval
 */
class CSVFileReaderTest extends PHPUnit_Framework_testCase
{

    private $data = array(
        [
            'Apartment Dörr',
            'Bolzmannweg 451, 05116 Hannover',
            '-1',
            'Scarlet Kusch-Linke',
            '8177354570',
            'http://www.paucek.com/search.htm.'
        ],
        [
            'Sölzer',
            'Margit-Stahr-Allee 9/6, 59321 München',
            '6',
            'Lise Dussen van-Wieloch',
            '+49(0)2855 26327',
            '.http://stumpf.com/post.php'
        ],
        [
            'Gibson',
            '63847 Lowe Knoll, East Maxine, WA 97030-4876',
            '5',
            'Dr. Sinda Wyman',
            '1-270-665-9933x1626',
            'http://www.paucek.com/йййй.htm.',
        ],
        [
            'Hostel Döhn',
            'Daria-Hesse-Straße 60, 82691 Neustadtm Rübenberge',
            '1',
            'Carol Kroker',
            '6810594067',
            'http://www.weiss.de/post/'
        ],
        [
            'Hostel Trommler',
            'Gutegasse 093, 37944 Augsburg',
            '2',
            'Dipl.-Ing. Hendrick Matthäi',
            '+49 (0) 8330 489285',
            'http://hotel.de/tag/main/category/',
        ]
    );

    /**
     * Creates Reflection class for testing private methods.
     */
    protected static function getMethod($obj, $name)
    {
        $class = new ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    /**
     * Invokes the method once with non ascii data another one with ascii data 
     */
    public function testAsciiEncodingValidate()
    {
        $obj = new CSVFileReader();
        $asciiEncodingValidate = self::getMethod($obj, 'asciiEncodingValidate');

        $res = $asciiEncodingValidate->invokeArgs($obj, array($this->data[0]));
        $this->assertFalse($res);
        $res = $asciiEncodingValidate->invokeArgs($obj, array($this->data[2]));
        $this->assertTrue($res);
    }
    
    /**
     * Invokes the method with correct and wrong rating data. 
     */
    public function testRatingValidate()
    {
        $obj = new CSVFileReader();
        $ratingValidate = self::getMethod($obj, 'ratingValidate');

        $res = $ratingValidate->invokeArgs($obj, array($this->data[0]));
        $this->assertFalse($res);
        $res = $ratingValidate->invokeArgs($obj, array($this->data[1]));
        $this->assertFalse($res);
        $res = $ratingValidate->invokeArgs($obj, array($this->data[2]));
        $this->assertTrue($res);
    }

    /**
     * Invokes the method with correct and wrong uri data. 
     */
    public function testUrlValidate()
    {
        $obj = new CSVFileReader();
        $urlValidate = self::getMethod($obj, 'urlValidate');

        $res = $urlValidate->invokeArgs($obj, array($this->data[0]));
        $this->assertFalse($res);
        $res = $urlValidate->invokeArgs($obj, array($this->data[1]));
        $this->assertFalse($res);
        $res = $urlValidate->invokeArgs($obj, array($this->data[2]));
        $this->assertFalse($res);
        $res = $urlValidate->invokeArgs($obj, array($this->data[3]));
        $this->assertTrue($res);
    }

    /**
     * Invokes the method and checks fileData content 
     * if validation successfully passed the count of items in fileData should grow up.
     */
    public function testValidateAndCollectData()
    {
        $obj = new CSVFileReader();
        $obj->setHeader(['name', 'address', 'stars', 'contact', 'phone', 'uri']);
        $validateAndCollectData = self::getMethod($obj, 'validateAndCollectData');
        $validateAndCollectData->invokeArgs($obj, array($this->data[0]));
        $res = $obj->getFileData();
        $this->assertCount(0, $res);
        
        $validateAndCollectData->invokeArgs($obj, array($this->data[1]));
        $res = $obj->getFileData();
        $this->assertCount(0, $res);

        $validateAndCollectData->invokeArgs($obj, array($this->data[4]));
        $res = $obj->getFileData();
        $this->assertCount(1, $res);
    }

    /**
     * Sorts data from the file by different fields and checks the results.
     */
    public function testDataSort()
    {
        $obj = new CSVFileReader();
        $obj->loadFileContent('test/data/hotels.csv');

        $obj->dataSort('name');
        $res = $obj->getFileData();
        $this->assertEquals($res[0]['name'], 'Apartment Ruggiero Giordano');
        $this->assertEquals($res[1]['name'], 'Comfort Inn Reichel');
        $this->assertEquals($res[2]['name'], 'Diaz');
        $this->assertEquals($res[15]['name'], 'The Rolland');
        $this->assertEquals($res[16]['name'], 'The Zimmer');
        
        $obj->dataSort('stars');
        $res = $obj->getFileData();
        $this->assertEquals($res[0]['stars'], '1');
        $this->assertEquals($res[4]['stars'], '2');
        $this->assertEquals($res[7]['stars'], '3');
        $this->assertEquals($res[9]['stars'], '4');
        $this->assertEquals($res[16]['stars'], '5');
        
        $obj->dataSort('contact');
        $res = $obj->getFileData();
        $this->assertEquals($res[0]['contact'], 'Alex Henry');
        $this->assertEquals($res[1]['contact'], 'Arlene Hornig');
        $this->assertEquals($res[2]['contact'], 'Benedetta Caputo');
        $this->assertEquals($res[3]['contact'], 'Clémence Hoarau');
        $this->assertEquals($res[16]['contact'], 'Victor Bodin-Leleu');


    }

}
