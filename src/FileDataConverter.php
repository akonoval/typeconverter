<?php

require 'input/CSVFileReader.php';
require 'output/JSONFileWriter.php';
require 'output/XMLFileWriter.php';

/*
 * Tool that converts the data from one format to other formats.
 */

/**
 * FileDataConverter Class
 *
 * @author Andrii Konoval
 */
class FileDataConverter
{

    private $config;
    public $fileReader;
    public $fileWriter;
    private $supportedFotmats = array(
        'xml' => 'data/%s.xml',
        'json' => 'data/%s.json'
    );

    /**
     * FileDataConverter Class Constructor
     *
     * The constructor takes in all the arguments via a single array.
     *
     * @param array $params parameters of the class
     * @return object
     */
    public function __construct($params = array())
    {
        $this->config = array(
            'inputFile' => 'data/hotels.csv',
            'outputFileName' => 'hotels',
            'convertToFormat' => 'all', //'all' as all available formats
            //'sortBy' => 'stars'
        );

        // Merge passed in params with defaults for config.
        $this->config = array_merge($this->config, $params);

        if (!isset($this->config['inputFile'])) {
            throw new Exception('FileDataConverter Class missing "inputFile" parameter.');
        }

        $this->fileReader = new CSVFileReader();
       
    }

    /**
     * Loads file content and converts
     * according predefined in the constructor parameters.
     */
    public function runFileConversion()
    {
        $this->fileReader->loadFileContent($this->config['inputFile']);

        if (isset($this->config['sortBy'])) {
            $this->fileReader->dataSort($this->config['sortBy']);
        }

        if ($this->config['convertToFormat'] == 'all') {
            foreach ($this->supportedFotmats as $format => $fileName) {
                $this->fileWriter($format, $fileName);
            }
        } else {
            $format = $this->config['convertToFormat'];
            $this->fileWriter($format, $this->supportedFotmats[$format]);
        }
    }

    /**
     * Writes uploaded data to file with another format.
     * 
     * @param string $convertToFormat conversion format
     * @param string $fileName the name of the file to print data
     */
    public function fileWriter($convertToFormat, $fileName)
    {
        switch ($convertToFormat) {
            case 'xml': $this->fileWriter = new XMLFileWriter($this->fileReader->getFileData());
                break;
            case 'json': $this->fileWriter = new JSONFileWriter($this->fileReader->getFileData());
                break;
            default:
                throw new Exception('Invalid or not supported format' . $convertToFormat);
        }
        $fName = sprintf($fileName, $this->config['outputFileName']);

        $this->fileWriter->printDataToFile($fName);
    }
}