<?php

namespace TeaminmediasPluswerk\KeSearch\FileParser;

use Contemas\DeveloperTools\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class OfficeXmlFileParser
 * @package TeaminmediasPluswerk\KeSearch\FileParser
 */
class DocFileParser extends AbstractFileParser
{
    /**
     * OfficeXmlFileParser constructor.
     * @param \tx_kesearch_lib_fileinfo $fileInfo
     */
    public function __construct(\tx_kesearch_lib_fileinfo $fileInfo)
    {
        parent::__construct($fileInfo);
        $this->initializeCatdoc();
    }

    /**
     * @return string
     */
    public function getContent()
    {
        // create the tempfile which will contain the content
        $tempFileName = GeneralUtility::tempnam('doc_files-Indexer');

        // Delete if exists, just to be safe.
        @unlink($tempFileName);

        // generate and execute the pdftotext commandline tool
        $cmd = $this->app['catdoc'] . ' -s8859-1 -dutf-8 ' . escapeshellarg($this->fileInfo->getPathAndFilename()) . ' > ' . escapeshellarg($tempFileName);
        CommandUtility::exec($cmd);
        
        // check if the tempFile was successfully created
        if (@is_file($tempFileName)) {
            $content = GeneralUtility::getUrl($tempFileName);
            unlink($tempFileName);
        } else {
            return false;
        }

        // check if content was found
        if (strlen($content)) {
            return $content;
        } else {
            return false;
        }
    }
}