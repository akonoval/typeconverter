<?php

require('src/FileDataConverter.php');

$params = array(
    // the name of the file with csv data
    'inputFile' => 'data/hotels.csv',
    
    // the name of the result file without extension,
    // in case the name is 'hotels' you can find the converted data in 
    // 'data/hotels.xml' or 'data/hotels.json'
    'outputFileName' => 'hotels',
    
    // format for conversion
    // 'json' - converts data only in 'json' format
    // 'xml'  - converts data only in 'xml'  format
    // 'all'  - converts data in all available formats, 
    // in our case 'json' and 'xml'
    'convertToFormat' => 'all',
    
    // 'sortBy' can contain any name of column from the header
    // in case 'sortBy' correctly set,
    // the result file data will be sorted by the set column 
    'sortBy' => 'stars'
);

// if no parameters set the FileDataConverter runs with default parameters
// 'inputFile'       => 'data/hotels.csv',
// 'outputFileName'  => 'hotels',
// 'convertToFormat' => 'all'
$cs = new FileDataConverter($params);
$cs->runFileConversion();

// prints not valid data to 'lastcase.log' file
$cs->fileReader->printLogToFile();
