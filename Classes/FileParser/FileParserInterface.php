<?php

namespace TeaminmediasPluswerk\KeSearch\FileParser;

/**
 * Interface FileParserInterface
 * @package TeaminmediasPluswerk\KeSearch\FileParser
 */
interface FileParserInterface
{
    /**
     * @return string
     */
    public function getContent();

    /**
     * @return array
     */
    public function getErrors();
}