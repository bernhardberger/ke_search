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

        switch ($this->fileInfo->getExtension()) {
            case 'docx':
            case 'dotx':
                // Read document.xml:
                $cmd = $this->app['unzip'] . ' -p ' . escapeshellarg($absFile) . ' word/document.xml';
                break;
            case 'ppsx':
            case 'pptx':
            case 'potx':
                // Read slide1.xml:
                $cmd = $this->app['unzip'] . ' -p ' . escapeshellarg($absFile) . ' ppt/slides/slide1.xml';
                break;
            case 'xlsx':
            case 'xltx':
                // Read sheet1.xml:
                $cmd = $this->app['unzip'] . ' -p ' . escapeshellarg($absFile) . ' xl/worksheets/sheet1.xml';
                break;
            default:
                return false;
        }

        CommandUtility::exec($cmd, $res);
        $content_xml = implode(LF, $res);
        unset($res);
        $content = trim(strip_tags(str_replace('<', ' <', $content_xml)));

        // check if content was found
        if (strlen($content)) {
            return $content;
        } else {
            return false;
        }
    }
}