<?php

namespace TeaminmediasPluswerk\KeSearch\UserFunc;

/**
 * Class ItemsProcFunc
 * @package TeaminmediasPluswerk\KeSearch\UserFunc
 */
class ItemsProcFunc {

    /**
     * @param array $config
     * @param $pObj
     *
     * @return array
     */
    public function getFileGroups(&$config, &$pObj)
    {
        $registeredFileGroups = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['file_parser_groups'];

        foreach ($registeredFileGroups as $key => $fileGroup) {
            $label = sprintf('%s (%s)', $fileGroup['label'], join(',.', $fileGroup['extensions']));

            $config['items'][] = array(
                0 => $label,
                1 => $key,
                2 => $fileGroup['icon']
            );
        }
    }
}