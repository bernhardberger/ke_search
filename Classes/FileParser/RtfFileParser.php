<?php

namespace TeaminmediasPluswerk\KeSearch\FileParser;

use TYPO3\CMS\Core\Utility\CommandUtility;

/**
 * Class OfficeXmlFileParser
 * @package TeaminmediasPluswerk\KeSearch\FileParser
 */
class XlsFileParser extends AbstractFileParser
{
    /**
     * OfficeXmlFileParser constructor.
     * @param \tx_kesearch_lib_fileinfo $fileInfo
     */
    public function __construct(\tx_kesearch_lib_fileinfo $fileInfo)
    {
        parent::__construct($fileInfo);
        $this->initalizeUnrtf();
    }

    /**
     * @return string
     */
    public function getContent()
    {
        $this->setLocaleForServerFileSystem();
        $cmd = $this->app['unrtf'] . ' ' . escapeshellarg($this->fileInfo->getPath());
        CommandUtility::exec($cmd, $res);
        $fileContent = implode(LF, $res);
        unset($res);
        $fileContent = $this->pObj->convertHTMLToUtf8($fileContent);
        $contentArr = $this->pObj->splitHTMLContent($fileContent);
        $this->setLocaleForServerFileSystem(true);
    }
}