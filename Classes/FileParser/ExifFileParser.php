<?php

namespace TeaminmediasPluswerk\KeSearch\FileParser;

/**
 * Class HtmlFileParser
 * @package TeaminmediasPluswerk\KeSearch\FileParser
 */
class ExifFileParser extends AbstractFileParser {

    /**
     * @return string
     */
    public function getContent()
    {
        $absFile = $this->fileInfo->getPathAndFilename();

        $this->setLocaleForServerFileSystem();
        // PHP EXIF
        if (function_exists('exif_read_data')) {
            $exif = @exif_read_data($absFile, 'IFD0');
        } else {
            $exif = false;
        }
        if ($exif) {
            $comment = trim($exif['COMMENT'][0] . ' ' . $exif['ImageDescription']);
        } else {
            $comment = '';
        }
        $contentArr = $this->splitRegularContent($comment);
        $contentArr['title'] = basename($absFile);
        // Make sure the title doesn't expose the absolute path!
        $this->setLocaleForServerFileSystem(true);

        $content = $contentArr['body'];
        return strlen($content) ? $content : false;
    }
}