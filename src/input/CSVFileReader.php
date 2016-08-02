<?php

/**
 * Loads and validates data from cvs file.
 *
 * @author Andrii Konoval
 */
class CSVFileReader
{

    const LOGFILENAME = 'lastcase.log';
    const NAME_COLUMN = 0;
    const ADDRESS_COLUMN = 1;
    const STARS_COLUMN = 2;
    const CONTACT_COLUMN = 3;
    const PHONE_COLUMN = 4;
    const URL_COLUMN = 5;

    private $filePath;
    private $fileData = array();
    private $brockenData = array();
    private $header = array();

    /**
     * Returns loaded data.
     */
    public function getFileData()
    {
        return $this->fileData;
    }

    /**
     * Sets the header.
     */
    public function setHeader($h = array())
    {
        return $this->header = $h;
    }

    /**
     * Loads and validates data from the file.
     * 
     * @param string $fPath the name of the file with a data
     */
    public function loadFileContent($fPath)
    {
        if (!file_exists($fPath)) {
            throw new Exception(sprintf('CSV File read failed.  The file [%s] not exists.', $fPath));
        }

        $this->filePath = $fPath;


        if (($handle = fopen($this->filePath, "r")) === FALSE) {
            return FALSE;
        }

        $this->setHeader(fgetcsv($handle, 1000, ","));
        if (empty($this->header)) {
            return FALSE;
        }

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $this->validateAndCollectData($data);
        }

        fclose($handle);
        printf("File '%s' was loaded and validated. \n", $this->filePath);
    }

    /**
     * Loads and validates an array-row.
     *     
     * @param array $data the row from the global list
     */
    private function validateAndCollectData($data)
    {
        static $row = 0;
        $num = count($data);

        if ($this->asciiEncodingValidate($data) && $this->ratingValidate($data) && $this->urlValidate($data)) {
            for ($col = 0; $col < $num; $col++) {
                $this->fileData[$row][$this->header[$col]] = $data[$col];
            }
            $row++;
        }
    }

    /**
     * Checks hotel names for containing non-ASCII characters.
     * 
     * @param array $data the row from the global list
     */
    private function asciiEncodingValidate($data)
    {
        $encoding = "ASCII";
        if (true === mb_check_encoding($data[self::NAME_COLUMN], $encoding)) {
            return TRUE;
        } else {
            $this->brockenData['non_ascii_name'][] = $data;
        }

        return FALSE;
    }

    /**
     * Checks ratings if they are a numbers from 0 to 5 stars.
     * 
     * @param array $data the row from the global list
     */
    private function ratingValidate($data)
    {
        // make checking if data[0] is name and data[2] is stars
        if ($data[self::STARS_COLUMN] >= 0 && $data[self::STARS_COLUMN] <= 5) {
            return TRUE;
        } else {
            $this->brockenData['rating_issue'][] = $data;
        }

        return FALSE;
    }

    /**
     * Validates hotel URL.
     * 
     * @param array $data the row from the global list
     */
    private function urlValidate($data)
    {
        $url = $data[self::URL_COLUMN];
        if (!filter_var($url, FILTER_VALIDATE_URL) === false && !preg_match('/^\.|\.$/', $url)) {
            return TRUE;
        } else {
            $this->brockenData['not_valid_url'][] = $data;
        }

        return FALSE;
    }

    /**
     * The comparison function for dataSort
     * 
     * @param array $a element for comparison
     * @param array $b element for comparison
     */
    private function sortCompare($a, $b)
    {
        return strcmp($a[$this->sortBy], $b[$this->sortBy]);
    }

    /**
     * Sorts the data by set field.
     * 
     * @param string $fieldName the column for sorting
     */
    public function dataSort($fieldName)
    {
        if (in_array($fieldName, $this->header)) {
            $this->sortBy = $fieldName;
            usort($this->fileData, array("CSVFileReader", "sortCompare"));
        }
    }
    
    /**
     * Prints all not valid data to log file.
     */
    public function printLogToFile()
    {
        file_put_contents(self::LOGFILENAME, "=== " . date('Y-m-d H:i:s') . " === \n");
        if (!empty($this->brockenData)) {
            file_put_contents(self::LOGFILENAME, var_export($this->brockenData, TRUE), FILE_APPEND);
        }
        printf("Printed all not valid data to '%s' file. \n", self::LOGFILENAME);
    }

}
