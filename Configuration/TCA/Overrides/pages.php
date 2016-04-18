<?php

$tempColumns = array(
    'tx_kesearch_tags' => array(
        'exclude' => 0,
        'label' => 'LLL:EXT:ke_search/Resources/Private/Language/locallang_db.xml:pages.tx_kesearch_tags',
        'config' => array(
            'type' => 'select',
            'renderType' => 'selectSingleBox',
            'size' => 10,
            'minitems' => 0,
            'maxitems' => 100,
            'items' => array(),
            'allowNonIdValues' => true,
            'itemsProcFunc' => 'user_filterlist->getListOfAvailableFiltersForTCA',
        )
    ),
);

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tempColumns);
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', 'tx_kesearch_tags;;;;1-1-1');