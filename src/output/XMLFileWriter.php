<?php

/**
 *  Writes data to xml file.
 *
 * @author Andrii Konoval
 */
class XMLFileWriter
{

    private $xmlData = array();

    /**
     * XMLFileWriter Class Constructor
     * 
     * Prepares data for saving - converts to 'xml' format.
     *
     * @param array $data converted data
     * @return object
     */
    public function __construct($data)
    {
        if (!empty($data)) {
            $doc = new DOMDocument('1.0');
            $doc->formatOutput = true;

            $root = $doc->createElement('hotels');
            $root = $doc->appendChild($root);

            foreach ($data as $values) {
                $this->addXMLValue($doc, $root, $values);
            }

            $this->xmlData = $doc->saveXML() . "\n";
        }
    }

    /**
     * Adds new value to xml array.
     * 
     * @param Object $doc DOMDocument object 
     * @param DOMElement $root DOM element
     * @param array $data the row
     */
    private function addXMLValue(&$doc, &$root, $data)
    {
        if (empty($data)) {
            return FALSE;
        }

        $valueElem = $doc->createElement('value');
        $valueElem = $root->appendChild($valueElem);

        foreach ($data as $key => $value) {
            $elem = $doc->createElement($key);
            $elem = $valueElem->appendChild($elem);
            $text = $doc->createTextNode($value);
            $text = $elem->appendChild($text);
        }

        return TRUE;
    }

    /**
     * Returns the converted data.
     */
    public function getXmlData()
    {
        return $this->xmlData;
    }

    /**
     * Prints the converted data to the file.
     * 
     * @param string $fName the filename for printing
     */
    public function printDataToFile($fName)
    {
        file_put_contents($fName, $this->getXmlData());
        printf("Data was saved to '%s' file. \n", $fName);
    }

}
