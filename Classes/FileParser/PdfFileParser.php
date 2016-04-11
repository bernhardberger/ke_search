<?php

namespace TeaminmediasPluswerk\KeSearch\FileParser;

use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Class OfficeXmlFileParser
 * @package TeaminmediasPluswerk\KeSearch\FileParser
 */
class PdfFileParser extends AbstractFileParser
{
    /**
     * OfficeXmlFileParser constructor.
     * @param \tx_kesearch_lib_fileinfo $fileInfo
     */
    public function __construct(\tx_kesearch_lib_fileinfo $fileInfo)
    {
        parent::__construct($fileInfo);
        $this->initializePdfToText();
    }

    /**
     * @return string
     */
    public function getContent()
    {
        $absFile = $this->fileInfo->getPathAndFilename();

        // get PDF informations
        if (!$pdfInfo = $this->getPdfInfo($absFile)) {
            return false;
        }

        // proceed only of there are any pages found
        if ($this->isAppArraySet && MathUtility::convertToPositiveInteger($pdfInfo['pages'])) {

            // create the tempfile which will contain the content
            $tempFileName = GeneralUtility::tempnam('pdf_files-Indexer');

            // Delete if exists, just to be safe.
            @unlink($tempFileName);

            // generate and execute the pdftotext commandline tool
            $cmd = $this->app['pdftotext'] . ' -enc UTF-8 -q ' . escapeshellarg($absFile) . ' ' . escapeshellarg($tempFileName);

            CommandUtility::exec($cmd);

            // check if the tempFile was successfully created
            if (@is_file($tempFileName)) {
                $content = GeneralUtility::getUrl($tempFileName);
                unlink($tempFileName);
            } else {
                $this->addError('Content for file ' . $absFile . ' could not be extracted. Maybe it is encrypted?');

                // return empty string if no content was found
                $content = '';
            }

            return $this->removeEndJunk($content);
        } else {
            return false;
        }
    }

    /**
     * execute commandline tool pdfinfo to extract pdf informations from file
     *
     * @param string $file
     * @return array The pdf informations as array
     */
    public function getPdfInfo($file)
    {
        if ($this->fileInfo->getIsFile()) {
            if ($this->fileInfo->getExtension() === 'pdf' && $this->isAppArraySet) {
                $cmd = $this->app['pdfinfo'] . ' ' . escapeshellarg($file);
                \TYPO3\CMS\Core\Utility\CommandUtility::exec($cmd, $pdfInfoArray);
                $pdfInfo = $this->splitPdfInfo($pdfInfoArray);
                unset($pdfInfoArray);
                return $pdfInfo;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Analysing PDF info into a useable format.
     *
     * @param array $pdfInfoArray of PDF content, coming from the pdfinfo tool
     * @return array The pdf informations as array in a useable format
     */
    protected function splitPdfInfo($pdfInfoArray)
    {
        $res = array();
        if (is_array($pdfInfoArray)) {
            foreach ($pdfInfoArray as $line) {
                $parts = explode(':', $line, 2);
                if (count($parts) > 1 && trim($parts[0])) {
                    $res[strtolower(trim($parts[0]))] = trim($parts[1]);
                }
            }
        }
        return $res;
    }

    /**
     * Removes some strange char(12) characters and line breaks that then to occur in the end of the string from external files.
     *
     * @param string String to clean up
     * @return string Cleaned up string
     */
    protected function removeEndJunk($string)
    {
        return trim(preg_replace('/[' . LF . chr(12) . ']*$/', '', $string));
    }
}