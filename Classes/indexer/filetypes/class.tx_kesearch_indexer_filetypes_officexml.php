<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Bernhard Berger <b.berger@contemas.net>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * Plugin 'Faceted search' for the 'ke_search' extension.
 *
 * @author    Bernhard Berger
 * @package    TYPO3
 * @subpackage    tx_kesearch
 */
class tx_kesearch_indexer_filetypes_officexml extends tx_kesearch_indexer_types_file_unzip implements tx_kesearch_indexer_filetypes
{

    /**
     * get Content of .***x file
     *
     * @param string $absFile
     *
     * @return string The extracted content of the file
     */
    public function getContent($absFile)
    {
//        // create the tempfile which will contain the content
//        $tempFileName = TYPO3\CMS\Core\Utility\GeneralUtility::tempnam('officexml_files-Indexer');
//
//        // Delete if exists, just to be safe.
//        @unlink($tempFileName);

        $this->fileInfo->setFile($absFile);

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

        TYPO3\CMS\Core\Utility\CommandUtility::exec($cmd, $res);
        $content_xml = implode(LF, $res);
        unset($res);
        $content = trim(strip_tags(str_replace('<', ' <', $content_xml)));

//        $contentArr['body'] = $content;
        // Make sure the title doesn't expose the absolute path!
//        $contentArr['title'] = PathUtility::basename($absFile);
        // Meta information
//        $cmd = $this->app['unzip'] . ' -p ' . escapeshellarg($absFile) . ' docProps/core.xml';
//        TYPO3\CMS\Core\Utility\CommandUtility::exec($cmd, $res);
//        $meta_xml = implode(LF, $res);
//        unset($res);
//        $metaContent = GeneralUtility::xml2tree($meta_xml);
//        if (is_array($metaContent)) {
//            $contentArr['title'] .= ' ' . $metaContent['cp:coreProperties'][0]['ch']['dc:title'][0]['values'][0];
//            $contentArr['description'] = $metaContent['cp:coreProperties'][0]['ch']['dc:subject'][0]['values'][0];
//            $contentArr['description'] .= ' ' . $metaContent['cp:coreProperties'][0]['ch']['dc:description'][0]['values'][0];
//            $contentArr['keywords'] = $metaContent['cp:coreProperties'][0]['ch']['cp:keywords'][0]['values'][0];
//        }

        // check if content was found
        if (strlen($content)) {
            return $content;
        } else {
            return false;
        }
    }

}