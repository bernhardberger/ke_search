<?php

namespace TeaminmediasPluswerk\KeSearch\FileParser;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class AbstractFileParser
 * @package TeaminmediasPluswerk\KeSearch\FileParser
 */
abstract class AbstractFileParser implements FileParserInterface
{
    /**
     * @var array
     */
    protected $extConf;

    /**
     * @var \tx_kesearch_lib_fileinfo
     */
    protected $fileInfo;

    /**
     * @var array
     */
    protected $app = array();

    /**
     * @var bool
     */
    protected $isAppArraySet = false;

    /**
     * @var array
     */
    protected $errors = array();

    /**
     * Charset class object
     *
     * @var \TYPO3\CMS\Core\Charset\CharsetConverter
     */
    public $csObj;

    /**
     * Set when crawler is detected (internal)
     *
     * @var array
     */
    public $defaultContentArray = array(
        'title' => '',
        'description' => '',
        'keywords' => '',
        'body' => ''
    );

    /**
     * HTML code blocks to exclude from indexing
     *
     * @var string
     */
    public $excludeSections = 'script,style';

    /**
     * AbstractFileParser constructor.
     * @param \tx_kesearch_lib_fileinfo $fileInfo
     */
    public function __construct(\tx_kesearch_lib_fileinfo $fileInfo)
    {
        $this->fileInfo = $fileInfo;
        $this->extConf = \tx_kesearch_helper::getExtConf();
        $this->csObj = GeneralUtility::makeInstance('TYPO3\CMS\Core\Charset\CharsetConverter');
    }

    /**
     * @param string $appPath
     * @return string
     */
    protected function getOperatingSystemRelatedAppPath($appPath, $cmdName)
    {
        $sanitizedPath = rtrim($appPath, '/') . DIRECTORY_SEPARATOR;
        $exe = (TYPO3_OS === 'WIN') ? '.exe' : '';
        return $sanitizedPath . $cmdName . $exe;
    }

    /**
     * @param string $appPath
     * @param string $cmdName
     * @return bool
     */
    protected function initializeApp($appPath, $cmdName)
    {
        if ($appPath !== '') {
            $executablePath = $this->getOperatingSystemRelatedAppPath($appPath, $cmdName);

            if (is_executable($executablePath)) {
                $this->app[$cmdName] = $executablePath;
                return true;
            }
        }

        $this->addError('The path to ' . $cmdName . ' is not correctly set in the extension manager configuration. You can get the path with "which ' . $cmdName . '".');
        return false;
    }

    protected function initializePdfToText()
    {
        $pdftotextSuccess = $this->initializeApp($this->extConf['pathPdftotext'], 'pdftotext');
        $pdfinfoSuccess = $this->initializeApp($this->extConf['pathPdfinfo'], 'pdfinfo');

        if ($pdfinfoSuccess && $pdftotextSuccess) {
            $this->isAppArraySet = true;
        } else {
            $this->isAppArraySet = false;
        }
    }

    protected function initializeCatdoc()
    {
        $this->isAppArraySet = $this->initializeApp($this->extConf['pathCatdoc'], 'catdoc');
    }

    protected function initializeUnzip()
    {
        $this->isAppArraySet = $this->initializeApp($this->extConf['pathUnzip'], 'unzip');
    }

    protected function initializeCatPPT()
    {
        $this->isAppArraySet = $this->initializeApp($this->extConf['pathCatdoc'], 'catppt');
    }

    protected function initalizeXls2Csv()
    {
        $this->isAppArraySet = $this->initializeApp($this->extConf['pathCatdoc'], 'xls2csv');
    }

    protected function initalizeUnrtf()
    {
        $this->isAppArraySet = $this->initializeApp($this->extConf['pathUnrtf'], 'unrtf');
    }

    /**
     * @param string $errorMessage
     */
    protected function addError($errorMessage)
    {
        $this->errors[] = $errorMessage;
    }

    /**
     * @param $cmdName
     */
    protected function addPathError($cmdName)
    {
        $this->addError('The path to ' . $cmdName . ' is not correctly set in the extension manager configuration. You can get the path with "which ' . $cmdName . '".');
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Sets the locale for LC_CTYPE to $TYPO3_CONF_VARS['SYS']['systemLocale']
     * if $TYPO3_CONF_VARS['SYS']['UTF8filesystem'] is set.
     *
     * Parameter <code>$resetLocale</code> has to be FALSE and TRUE alternating for all calls.
     *
     * @staticvar string $lastLocale Stores the locale used before it is overridden by this method.
     * @param bool $resetLocale TRUE resets the locale to $lastLocale.
     * @return void
     * @throws \RuntimeException
     */
    protected function setLocaleForServerFileSystem($resetLocale = false)
    {
        static $lastLocale = null;

        if (!$GLOBALS['TYPO3_CONF_VARS']['SYS']['UTF8filesystem']) {
            return;
        }
        if ($resetLocale) {
            if ($lastLocale == null) {
                throw new \RuntimeException('Cannot reset locale to NULL.', 1357064326);
            }
            setlocale(LC_CTYPE, $lastLocale);
            $lastLocale = null;
        } else {
            if ($lastLocale !== null) {
                throw new \RuntimeException('Cannot set new locale as locale has already been changed before.',
                    1357064437);
            }
            $lastLocale = setlocale(LC_CTYPE, 0);
            setlocale(LC_CTYPE, $GLOBALS['TYPO3_CONF_VARS']['SYS']['systemLocale']);
        }
    }

    /**
     * Splits HTML content and returns an associative array, with title, a list of metatags, and a list of words in the body.
     *
     * @param string $content HTML content to index. To some degree expected to be made by TYPO3 (ei. splitting the header by ":")
     * @return array Array of content, having keys "title", "body", "keywords" and "description" set.
     * @see splitRegularContent()
     */
    public function splitHTMLContent($content)
    {
        // divide head from body ( u-ouh :) )
        $contentArr['body'] = stristr($content, '<body');
        $headPart = substr($content, 0, -strlen($contentArr['body']));
        // get title
        $this->embracingTags($headPart, 'TITLE', $contentArr['title'], $dummy2, $dummy);
        $titleParts = explode(':', $contentArr['title'], 2);
        $contentArr['title'] = trim(isset($titleParts[1]) ? $titleParts[1] : $titleParts[0]);
        // get keywords and description metatags
//        if ($this->conf['index_metatags']) {
//            $meta = array();
//            $i = 0;
//            while ($this->embracingTags($headPart, 'meta', $dummy, $headPart, $meta[$i])) {
//                $i++;
//            }
//            // @todo The code below stops at first unset tag. Is that correct?
//            for ($i = 0; isset($meta[$i]); $i++) {
//                $meta[$i] = GeneralUtility::get_tag_attributes($meta[$i]);
//                if (stristr($meta[$i]['name'], 'keywords')) {
//                    $contentArr['keywords'] .= ',' . $this->addSpacesToKeywordList($meta[$i]['content']);
//                }
//                if (stristr($meta[$i]['name'], 'description')) {
//                    $contentArr['description'] .= ',' . $meta[$i]['content'];
//                }
//            }
//        }
        // Process <!--TYPO3SEARCH_begin--> or <!--TYPO3SEARCH_end--> tags:
        $this->typoSearchTags($contentArr['body']);
        // Get rid of unwanted sections (ie. scripting and style stuff) in body
        $tagList = explode(',', $this->excludeSections);
        foreach ($tagList as $tag) {
            while ($this->embracingTags($contentArr['body'], $tag, $dummy, $contentArr['body'], $dummy2)) {
            }
        }
        // remove tags, but first make sure we don't concatenate words by doing it
        $contentArr['body'] = str_replace('<', ' <', $contentArr['body']);
        $contentArr['body'] = trim(strip_tags($contentArr['body']));
        $contentArr['keywords'] = trim($contentArr['keywords']);
        $contentArr['description'] = trim($contentArr['description']);
        // Return array
        return $contentArr;
    }

    /**
     * Splits non-HTML content (from external files for instance)
     *
     * @param string $content Input content (non-HTML) to index.
     * @return array Array of content, having the key "body" set (plus "title", "description" and "keywords", but empty)
     * @see splitHTMLContent()
     */
    public function splitRegularContent($content)
    {
        $contentArr = $this->defaultContentArray;
        $contentArr['body'] = $content;
        return $contentArr;
    }

    /**
     * Finds first occurrence of embracing tags and returns the embraced content and the original string with
     * the tag removed in the two passed variables. Returns FALSE if no match found. ie. useful for finding
     * <title> of document or removing <script>-sections
     *
     * @param string $string String to search in
     * @param string $tagName Tag name, eg. "script
     * @param string $tagContent Passed by reference: Content inside found tag
     * @param string $stringAfter Passed by reference: Content after found tag
     * @param string $paramList Passed by reference: Attributes of the found tag.
     * @return bool Returns FALSE if tag was not found, otherwise TRUE.
     */
    public function embracingTags($string, $tagName, &$tagContent, &$stringAfter, &$paramList)
    {
        $endTag = '</' . $tagName . '>';
        $startTag = '<' . $tagName;
        // stristr used because we want a case-insensitive search for the tag.
        $isTagInText = stristr($string, $startTag);
        // if the tag was not found, return FALSE
        if (!$isTagInText) {
            return false;
        }
        list($paramList, $isTagInText) = explode('>', substr($isTagInText, strlen($startTag)), 2);
        $afterTagInText = stristr($isTagInText, $endTag);
        if ($afterTagInText) {
            $stringBefore = substr($string, 0, strpos(strtolower($string), strtolower($startTag)));
            $tagContent = substr($isTagInText, 0, strlen($isTagInText) - strlen($afterTagInText));
            $stringAfter = $stringBefore . substr($afterTagInText, strlen($endTag));
        } else {
            $tagContent = '';
            $stringAfter = $isTagInText;
        }
        return true;
    }

    /**
     * Removes content that shouldn't be indexed according to TYPO3SEARCH-tags.
     *
     * @param string $body HTML Content, passed by reference
     * @return bool Returns TRUE if a TYPOSEARCH_ tag was found, otherwise FALSE.
     */
    public function typoSearchTags(&$body)
    {
        $expBody = preg_split('/\\<\\!\\-\\-[\\s]?TYPO3SEARCH_/', $body);
        if (count($expBody) > 1) {
            $body = '';
            foreach ($expBody as $val) {
                $part = explode('-->', $val, 2);
                if (trim($part[0]) == 'begin') {
                    $body .= $part[1];
                    $prev = '';
                } elseif (trim($part[0]) == 'end') {
                    $body .= $prev;
                } else {
                    $prev = $val;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Converts a HTML document to utf-8
     *
     * @param string $content HTML content, any charset
     * @param string $charset Optional charset (otherwise extracted from HTML)
     * @return string Converted HTML
     */
    public function convertHTMLToUtf8($content, $charset = '')
    {
        // Find charset:
        $charset = $charset ?: $this->getHTMLcharset($content);
        $charset = $this->csObj->parse_charset($charset);
        // Convert charset:
        if ($charset && $charset !== 'utf-8') {
            $content = $this->csObj->utf8_encode($content, $charset);
        }
        // Convert entities, assuming document is now UTF-8:
        return $this->csObj->entities_to_utf8($content, true);
    }

    /**
     * Extract the charset value from HTML meta tag.
     *
     * @param string $content HTML content
     * @return string The charset value if found.
     */
    public function getHTMLcharset($content)
    {
        if (preg_match('/<meta[[:space:]]+[^>]*http-equiv[[:space:]]*=[[:space:]]*["\']CONTENT-TYPE["\'][^>]*>/i', $content, $reg)) {
            if (preg_match('/charset[[:space:]]*=[[:space:]]*([[:alnum:]-]+)/i', $reg[0], $reg2)) {
                return $reg2[1];
            }
        }

        return '';
    }


}