<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'ke_search',
    'Configuration/TypoScript',
    'Faceted Search'
);

// Show FlexForm field in plugin configuration
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['ke_search_pi1'] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['ke_search_pi2'] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['ke_search_pi3'] = 'pi_flexform';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['ke_search_pi1'] = 'layout,select_key';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['ke_search_pi2'] = 'layout,select_key';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['ke_search_pi3'] = 'layout,select_key';

// Configure FlexForm field
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('ke_search_pi1', 'FILE:EXT:ke_search/Configuration/Flexforms/Pi1.xml');
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('ke_search_pi2', 'FILE:EXT:ke_search/Configuration/Flexforms/Pi2.xml');
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('ke_search_pi3', 'FILE:EXT:ke_search/Configuration/Flexforms/Pi3.xml');