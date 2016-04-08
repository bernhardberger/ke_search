<?php

namespace TeaminmediasPluswerk\KeSearch\FileParser;

use TYPO3\CMS\Core\Utility\CommandUtility;

/**
 * Class OfficeXmlFileParser
 * @package TeaminmediasPluswerk\KeSearch\FileParser
 */
class OfficeXmlFileParser extends AbstractFileParser
{
    /**
     * OfficeXmlFileParser constructor.
     * @param \tx_kesearch_lib_fileinfo $fileInfo
     */
    public function __construct(\tx_kesearch_lib_fileinfo $fileInfo)
    {
        parent::__construct($fileInfo);
        $this->initializeUnzip();
    }

    /**
     * @return string
     */
    public function getContent()
    {
        $absFile = $this->fileInfo->getPath();

        if ($this->app['unzip']) {
            $this->setLocaleForServerFileSystem();
            
            // Read content.xml:
            $cmd = $this->app['unzip'] . ' -p ' . escapeshellarg($absFile) . ' content.xml';
            CommandUtility::exec($cmd, $res);
            $content_xml = implode(LF, $res);
            unset($res);

            $content = trim(strip_tags(str_replace('<', ' <', $content_xml)));

            $this->setLocaleForServerFileSystem(true);

            return $content;
        }

        return false;
    }
}