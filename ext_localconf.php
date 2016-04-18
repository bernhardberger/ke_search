<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

// register cli-script
if (TYPO3_MODE=='BE')    {
    $TYPO3_CONF_VARS['SC_OPTIONS']['GLOBAL']['cliKeys'][$_EXTKEY] = array('EXT:' . $_EXTKEY . '/cli/class.cli_kesearch.php','_CLI_kesearch');
}


TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:ke_search/Configuration/TSConfig/pageTS.ts">');

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY, 'pi1/class.tx_kesearch_pi1.php', '_pi1', 'list_type', 0);
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY, 'pi2/class.tx_kesearch_pi2.php', '_pi2', 'list_type', 0);
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY, 'pi3/class.tx_kesearch_pi3.php', '_pi3', 'list_type', 0);

// use hooks for generation of sortdate values
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['registerAdditionalFields'][]    = 'EXT:ke_search/Classes/hooks/class.user_kesearchhooks.php:user_kesearch_sortdate';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['modifyPagesIndexEntry'][]       = 'EXT:ke_search/Classes/hooks/class.user_kesearchhooks.php:user_kesearch_sortdate';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['modifyYACIndexEntry'][]         = 'EXT:ke_search/Classes/hooks/class.user_kesearchhooks.php:user_kesearch_sortdate';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['modifyContentIndexEntry'][]     = 'EXT:ke_search/Classes/hooks/class.user_kesearchhooks.php:user_kesearch_sortdate';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['modifyTemplaVoilaIndexEntry'][] = 'EXT:ke_search/Classes/hooks/class.user_kesearchhooks.php:user_kesearch_sortdate';

// add scheduler task
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['TeaminmediasPluswerk\\KeSearch\\Task\\IndexerTask'] = array(
    'extension'        => $_EXTKEY,
    'title'            => 'LLL:EXT:'.$_EXTKEY.'/Resources/Private/Language/locallang_be.xlf:indexerTask.title',
    'description'      => 'LLL:EXT:'.$_EXTKEY.'/Resources/Private/Language/locallang_be.xlf:indexerTask.description',
);


$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['file_parser'] = array(
    // PDF
    'pdf'  => 'TeaminmediasPluswerk\KeSearch\FileParser\PdfFileParser',
    // MS Word
    'doc'  => 'TeaminmediasPluswerk\KeSearch\FileParser\DocFileParser',
    // MS Word >= 2007
    'docx' => 'TeaminmediasPluswerk\KeSearch\FileParser\OfficeXmlFileParser',
    'dotx' => 'TeaminmediasPluswerk\KeSearch\FileParser\OfficeXmlFileParser',
    // MS PowerPoint
    'ppt'  => 'TeaminmediasPluswerk\KeSearch\FileParser\PptFileParser',
    'pps'  => 'TeaminmediasPluswerk\KeSearch\FileParser\PptFileParser',
    // MS PowerPoint >= 2007
    'ppsx' => 'TeaminmediasPluswerk\KeSearch\FileParser\OfficeXmlFileParser',
    'pptx' => 'TeaminmediasPluswerk\KeSearch\FileParser\OfficeXmlFileParser',
    'potx' => 'TeaminmediasPluswerk\KeSearch\FileParser\OfficeXmlFileParser',
    // MS Excel
    'xls'  => 'TeaminmediasPluswerk\KeSearch\FileParser\XlsFileParser',
    // MS Excel >= 2007
    'xlsx' => 'TeaminmediasPluswerk\KeSearch\FileParser\OfficeXmlFileParser',
    'xltx' => 'TeaminmediasPluswerk\KeSearch\FileParser\OfficeXmlFileParser',
    // OpenDocument
    'sxc'  => 'TeaminmediasPluswerk\KeSearch\FileParser\OpenDocumentFileParser',
    'sxi'  => 'TeaminmediasPluswerk\KeSearch\FileParser\OpenDocumentFileParser',
    'sxw'  => 'TeaminmediasPluswerk\KeSearch\FileParser\OpenDocumentFileParser',
    'ods'  => 'TeaminmediasPluswerk\KeSearch\FileParser\OpenDocumentFileParser',
    'odp'  => 'TeaminmediasPluswerk\KeSearch\FileParser\OpenDocumentFileParser',
    'odt'  => 'TeaminmediasPluswerk\KeSearch\FileParser\OpenDocumentFileParser',
    // Rich Text Format
    'rtf'  => 'TeaminmediasPluswerk\KeSearch\FileParser\RtfFileParser',
    // Plaintext
    'txt'  => 'TeaminmediasPluswerk\KeSearch\FileParser\PlaintextFileParser',
    'csv'  => 'TeaminmediasPluswerk\KeSearch\FileParser\PlaintextFileParser',
    // HTML
    'html' => 'TeaminmediasPluswerk\KeSearch\FileParser\HtmlFileParser',
    'htm' => 'TeaminmediasPluswerk\KeSearch\FileParser\HtmlFileParser',
    // XML
    'xml'  => 'TeaminmediasPluswerk\KeSearch\FileParser\XmlFileParser',
    // EXIF
    'jpg'  => 'TeaminmediasPluswerk\KeSearch\FileParser\ExifFileParser',
    'jpeg'  => 'TeaminmediasPluswerk\KeSearch\FileParser\ExifFileParser',
    'tif'  => 'TeaminmediasPluswerk\KeSearch\FileParser\ExifFileParser',
);