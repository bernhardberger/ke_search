<?php
interface tx_kesearch_indexer_filetypes {

	/**
	 * get Content of file
	 *
	 * @param string $absFile
	 *
*@return string The extracted content of the file
	 */
	public function getContent($absFile);
}
