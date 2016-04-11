<?php

namespace TeaminmediasPluswerk\KeSearch\FileParser;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class HtmlFileParser
 * @package TeaminmediasPluswerk\KeSearch\FileParser
 */
class XmlFileParser extends AbstractFileParser {

    /**
     * @return string
     */
    public function getContent()
    {
        $absFile = $this->fileInfo->getPathAndFilename();

        $this->setLocaleForServerFileSystem();
        // PHP strip-tags()
        $fileContent = GeneralUtility::getUrl($absFile);
        // Finding charset:
        preg_match('/^[[:space:]]*<\\?xml[^>]+encoding[[:space:]]*=[[:space:]]*["\'][[:space:]]*([[:alnum:]_-]+)[[:space:]]*["\']/i', substr($fileContent, 0, 200), $reg);
        $charset = $reg[1] ? $this->csObj->parse_charset($reg[1]) : 'utf-8';
        // Converting content:
        $fileContent = $this->convertHTMLToUtf8(strip_tags(str_replace('<', ' <', $fileContent)), $charset);
        $contentArr = $this->splitRegularContent($fileContent);
        $contentArr['title'] = basename($absFile);
        // Make sure the title doesn't expose the absolute path!
        $this->setLocaleForServerFileSystem(true);

        $content = $contentArr['body'];
        return strlen($content) ? $content : false;
    }
}