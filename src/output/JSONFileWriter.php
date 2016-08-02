<?php

/**
 *  Writes data to json file.
 *
 * @author Andrii Konoval
 */
class JSONFileWriter
{

    private $jsonData = array();

    /**
     * jsonFileWriter Class Constructor
     *
     * Prepares data for saving - converts to 'json' format.
     * 
     * @param array $data converted data
     * @return object
     */
    public function __construct($data)
    {
        if (!empty($data)) {
            $this->jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
    }

    /**
     * Returns the converted data.
     */
    public function getJsonData()
    {
        return $this->jsonData;
    }

    /**
     * Prints the converted data to the file.
     * 
     * @param string $fName the filename for printing
     */
    public function printDataToFile($fName)
    {
        file_put_contents($fName, $this->getJsonData());
        printf("Data was saved to '%s' file \n", $fName);
    }

}
