<?php

namespace TeaminmediasPluswerk\KeSearch\FileParser;

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
     * AbstractFileParser constructor.
     * @param \tx_kesearch_lib_fileinfo $fileInfo
     */
    public function __construct(\tx_kesearch_lib_fileinfo $fileInfo)
    {
        $this->fileInfo = $fileInfo;
        $this->extConf = \tx_kesearch_helper::getExtConf();
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
}