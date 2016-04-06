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
 * @author	Bernhard Berger
 * @package	TYPO3
 * @subpackage	tx_kesearch
 */
class tx_kesearch_indexer_types_file_unzip extends tx_kesearch_indexer_types_file implements tx_kesearch_indexer_filetypes {
    /**
     * class constructor
     */
    public function __construct() {
        parent::__construct($this->pObj);

        // check if path to catdoc is correct
        if ($this->extConf['pathUnzip']) {
            $pathUnzip = rtrim($this->extConf['pathUnzip'], '/') . '/';

            $exe = (TYPO3_OS == 'WIN') ? '.exe' : '';
            if (is_executable($pathUnzip . 'unzip' . $exe)) {
                $this->app['unzip'] = $pathUnzip . 'unzip' . $exe;
                $this->isAppArraySet = true;
            }
            else {
                $this->isAppArraySet = false;
            }
        }
        else {
            $this->isAppArraySet = false;
        }

        if (!$this->isAppArraySet) {
            $this->addError('The path to unzip is not correctly set in the extension manager configuration. You can get the path with "which unzip".');
        }
    }

    /**
     * get Content of file
     *
     * @param string $absFile
     *
     * @return string The extracted content of the file
     */
    public function getContent($absFile)
    {
        return false;
    }
}