<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 Dirk Wenzel <wenzel@cps-it.de>
 *  All rights reserved
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the text file GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


$readers = [
    new \Helhum\ConfigLoader\Reader\EnvironmentReader('TYPO3', '_')
];
$absoluteConfigFilePath = getenv('ENV_FILE_PATH');
if (!empty($absoluteConfigFilePath)) {
    $fileReader = new \Helhum\ConfigLoader\Reader\YamlFileReader($absoluteConfigFilePath);
    $readers[] = $fileReader;
}

// We let the loader load context and environment specific configuration
// No other code must go in here!
$configLoader = new \Helhum\ConfigLoader\ConfigurationLoader(
    $readers,
    [
        new \Helhum\ConfigLoader\Processor\PlaceholderValue()
    ]
);
$config = $configLoader->load();

if (!getenv('ENV_FILE_PATH')) {
    $config['DB']['Connections']['Default']['dbname'] = getenv('TYPO3_DB_NAME');
    $config['DB']['Connections']['Default']['host'] = getenv('TYPO3_DB_HOST');
    $config['DB']['Connections']['Default']['password'] = getenv('TYPO3_DB_PASS');
    $config['DB']['Connections']['Default']['user'] = getenv('TYPO3_DB_USER');
    $config['BE']['installToolPassword'] = getenv('TYPO3_INSTALL');
    $config['SYS']['encryptionKey'] = getenv('TYPO3_ENCRYPTION_KEY');
}

$GLOBALS['TYPO3_CONF_VARS'] = array_replace_recursive(
    $GLOBALS['TYPO3_CONF_VARS'],
    $config
);
