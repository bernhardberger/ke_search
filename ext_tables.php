<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

// help file
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_kesearch_filters', 'EXT:ke_search/locallang_csh.xml');

if (TYPO3_MODE == 'BE') {
	require_once(TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('ke_search') . 'Classes/lib/class.tx_kesearch_lib_items.php');

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'TeaminmediasPluswerk.' . $_EXTKEY,
		'web',          // Main area
		'backend_module',         // Name of the module
		'',             // Position of the module
		array(          // Allowed controller action combinations
			'BackendModule' => 'startIndexing,indexedContent,indexTableInformation,searchwordStatistics,clearSearchIndex,lastIndexingReport,alert',
		),
		array(          // Additional configuration
			'access'    => 'user,group',
			'icon'      => 'EXT:ke_search/Resources/Public/Icons/moduleicon.gif',
			'labels'    => 'LLL:EXT:ke_search/Resources/Private/Language/locallang_mod.xml',
		)
	);

	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_kesearch_pi1_wizicon'] = TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('ke_search') . 'pi1/class.tx_kesearch_pi1_wizicon.php';
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_kesearch_pi2_wizicon'] = TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('ke_search') . 'pi2/class.tx_kesearch_pi2_wizicon.php';
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_kesearch_pi3_wizicon'] = TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('ke_search') . 'pi3/class.tx_kesearch_pi3_wizicon.php';

	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['file_parser_groups'] = array(
		'pdf' => array(
			'label' => 'PDF',
			'icon' => 'EXT:ke_search/Resources/Public/Icons/FileExtensions/file_extension_pdf.png',
			'extensions' =>  array('pdf')
		),
		'doc' => array(
			'label' => 'Microsoft Word Document / OpenDocument-Text',
			'icon' => 'EXT:ke_search/Resources/Public/Icons/FileExtensions/file_extension_doc.png',
			'extensions' =>  array('doc', 'docx', 'dotx', 'odt', 'sxw')
		),
		'ppt' => array(
			'label' => 'Microsoft PowerPoint / OpenDocument-Presentation',
			'icon' => 'EXT:ke_search/Resources/Public/Icons/FileExtensions/file_extension_pps.png',
			'extensions' =>  array('ppt','pps','ppsx','pptx','potx', 'odp', 'sxi')
		),
		'xls' => array(
			'label' => 'Microsoft Excel / OpenDocument-Spreadsheet',
			'icon' => 'EXT:ke_search/Resources/Public/Icons/FileExtensions/file_extension_xls.png',
			'extensions' =>  array('xls', 'xlsx', 'xltx', 'ods', 'sxc')
		),
		'rtf' => array(
			'label' => 'Rich Text Format',
			'icon' => 'EXT:ke_search/Resources/Public/Icons/FileExtensions/file_extension_rtf.png',
			'extensions' =>  array('rtf')
		),
		'txt' => array(
			'label' => 'Plaintext',
			'icon' => 'EXT:ke_search/Resources/Public/Icons/FileExtensions/file_extension_txt.png',
			'extensions' =>  array('txt', 'csv')
		),
		'html' => array(
			'label' => 'HTML / XML',
			'icon' => 'EXT:ke_search/Resources/Public/Icons/FileExtensions/file_extension_html.png',
			'extensions' =>  array('html', 'htm')
		),
		'xml' => array(
			'label' => 'XML',
			'icon' => 'EXT:ke_search/Resources/Public/Icons/FileExtensions/file_extension_xpi.png',
			'extensions' =>  array('xml')
		),
		'exif' => array(
			'label' => 'EXIF (JPG, TIF)',
			'icon' => 'EXT:ke_search/Resources/Public/Icons/FileExtensions/file_extension_jpg.png',
			'extensions' =>  array('jpg', 'jpeg', 'tif')
		)
	);
}



TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(array(
	'LLL:EXT:ke_search/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('ke_search') . 'ext_icon.gif'), 'list_type'
);

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(array(
	'LLL:EXT:ke_search/locallang_db.xml:tt_content.list_type_pi2',
	$_EXTKEY . '_pi2',
	TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('ke_search') . 'ext_icon.gif'), 'list_type'
);

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(array(
	'LLL:EXT:ke_search/locallang_db.xml:tt_content.list_type_pi3',
	$_EXTKEY . '_pi3',
	TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('ke_search') . 'ext_icon.gif'), 'list_type'
);

// class for displaying the category tree for tt_news in BE forms.
if (TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('tt_news')) {
	include_once(TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('tt_news') . 'lib/class.tx_ttnews_TCAform_selectTree.php');
}
