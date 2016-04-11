<?php

namespace TeaminmediasPluswerk\KeSearch\FileParser;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class HtmlFileParser
 * @package TeaminmediasPluswerk\KeSearch\FileParser
 */
class HtmlFileParser extends AbstractFileParser {

    /**
     * @return string
     */
    public function getContent()
    {
        $absFile = $this->fileInfo->getPathAndFilename();

        $fileContent = GeneralUtility::getUrl($absFile);
        $fileContent = $this->convertHTMLToUtf8($fileContent);
        $contentArr = $this->splitHTMLContent($fileContent);

        $content = $contentArr['body'];

        return strlen($content) ? $content : false;
    }
}