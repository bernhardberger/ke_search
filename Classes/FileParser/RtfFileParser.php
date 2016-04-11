<?php

namespace TeaminmediasPluswerk\KeSearch\FileParser;

use TYPO3\CMS\Core\Utility\CommandUtility;

/**
 * Class OfficeXmlFileParser
 * @package TeaminmediasPluswerk\KeSearch\FileParser
 */
class RtfFileParser extends AbstractFileParser
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
        $cmd = $this->app['unrtf'] . ' ' . escapeshellarg($this->fileInfo->getPathAndFilename());
        CommandUtility::exec($cmd, $res);
        $fileContent = implode(LF, $res);

        $contentArr = $this->splitHTMLContent($fileContent);
        unset($res);

        $content = $contentArr['body'];

        $this->setLocaleForServerFileSystem(true);

        return strlen($content) ? $content : false;
    }
}