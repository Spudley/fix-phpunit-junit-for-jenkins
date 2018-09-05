<?php
/**
 * Read PHPUnit's junit XML and outut a modified version of it that will work with Jenkins' junit parser.
 */
$fixer = new fixPHPUnitJunitXMLForJenkins();
$fixer->loadXML(__DIR__ . '/logs/junit.xml');
$fixer->fixXML();
$fixer->saveXML(__DIR__ . '/logs/junit_fixed.xml');

die;

class fixPHPUnitJunitXMLForJenkins
{
    private $testsuites;

    public function loadXML($filename)
    {
        $this->testsuites = new SimpleXMLElement(file_get_contents($filename));
    }

    public function saveXML($filename)
    {
        $this->testsuites->asXML($filename);
    }

    public function fixXML()
    {
        $this->recursiveFix($this->testsuites);
    }

    private function recursiveFix($element)
    {
        //time attribute must be limited to three decimal places.
        if (isset($element['time'])) {
            $element['time'] = number_format((float)$element['time'], 3);
        }
        //these attributes are not valid according to JUnit.
        unset($element['assertions']);
        unset($element['class']);
        unset($element['file']);
        unset($element['line']);

        if ($element->count()) {
            foreach ($element as $child) {
                $this->recursiveFix($child);
            }
        }
    }
}