<?php
namespace In2\Femanager\Utility\Eid;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Core\Bootstrap;
use TYPO3\CMS\Frontend\Utility\EidUtility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Alex Kellner <alexander.kellner@in2code.de>, in2code.de
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 ***************************************************************/

/**
 * This class could called with AJAX via eID
 *
 * @author	Alex Kellner <alexander.kellner@in2code.de>, in2code.de
 * @package	TYPO3
 * @subpackage	EidFileDelete
 */
class FileDelete {

	/**
	 * configuration
	 *
	 * @var array
	 */
	protected $configuration;

	/**
	 * bootstrap
	 *
	 * @var array
	 */
	protected $bootstrap;

	/**
	 * Generates the output
	 *
	 * @return string		rendered action
	 */
	public function run() {
		return $this->bootstrap->run('', $this->configuration);
	}

	/**
	 * Initialize Extbase
	 *
	 * @param array $TYPO3_CONF_VARS
	 */
	public function __construct($TYPO3_CONF_VARS) {
		$this->configuration = array(
			'pluginName' => 'Pi1',
			'vendorName' => 'In2',
			'extensionName' => 'Femanager',
			'controller' => 'User',
			'action' => 'fileDelete',
			'mvc' => array(
				'requestHandlers' => array(
					'TYPO3\CMS\Extbase\Mvc\Web\FrontendRequestHandler' => 'TYPO3\CMS\Extbase\Mvc\Web\FrontendRequestHandler'
				)
			),
			'settings' => array()
		);
		$_POST['tx_femanager_pi1']['action'] = 'fileDelete';
		$_POST['tx_femanager_pi1']['controller'] = 'User';

		$this->bootstrap = new Bootstrap();

		$userObj = EidUtility::initFeUser();
		$pid = (GeneralUtility::_GET('id') ? GeneralUtility::_GET('id') : 1);
		$GLOBALS['TSFE'] = GeneralUtility::makeInstance(
			'TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController',
			$TYPO3_CONF_VARS,
			$pid,
			0,
			TRUE
		);
		$GLOBALS['TSFE']->connectToDB();
		$GLOBALS['TSFE']->fe_user = $userObj;
		$GLOBALS['TSFE']->id = $pid;
		$GLOBALS['TSFE']->determineId();
		$GLOBALS['TSFE']->initTemplate();
		$GLOBALS['TSFE']->getConfigArray();
	}
}

$eid = GeneralUtility::makeInstance('In2\\Femanager\\Utility\\Eid\\FileDelete', $GLOBALS['TYPO3_CONF_VARS']);
echo $eid->run();