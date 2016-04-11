<?php

namespace TeaminmediasPluswerk\KeSearch\FileParser;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class PlaintextFileParser extends AbstractFileParser {

    /**
     * @return string
     */
    public function getContent()
    {
        $absFile = $this->fileInfo->getPathAndFilename();

        $this->setLocaleForServerFileSystem();
        // Raw text
        $content = GeneralUtility::getUrl($absFile);
        // @todo Implement auto detection of charset (currently assuming utf-8)
        $contentCharset = 'utf-8';
        $content = $this->convertHTMLToUtf8($content, $contentCharset);
        $contentArr = $this->splitRegularContent($content);
        $contentArr['title'] = basename($absFile);
        // Make sure the title doesn't expose the absolute path!
        $this->setLocaleForServerFileSystem(true);
    }
}