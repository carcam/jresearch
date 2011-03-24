<?php
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

define('_COOPERATION_IMAGE_MAX_WIDTH_', 1024);
define('_COOPERATION_IMAGE_MAX_HEIGHT_', 768);

define('_FACILITY_IMAGE_MAX_WIDTH_', 1024);
define('_FACILITY_IMAGE_MAX_HEIGHT_', 768);

define('_PROJECT_IMAGE_MAX_WIDTH_', 1024);
define('_PROJECT_IMAGE_MAX_HEIGHT_', 768);

define('_MEMBER_IMAGE_MAX_WIDTH_', 1024);
define('_MEMBER_IMAGE_MAX_HEIGHT_', 768);

define('_JRESEARCH_VERSION_', '1.2.3 Stable');
define('_JRESEARCH_BIBUTILS_VERSION_', '4.12');
define('_JRESEARCH_UPGRADER_SUPPORT_', true);

define('JRESEARCH_COMPONENT_ADMIN', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jresearch');
define('JRESEARCH_COMPONENT_SITE', JPATH_SITE.DS.'components'.DS.'com_jresearch');

if (!defined('PHP_EOL'))
{
    switch (strtoupper(substr(PHP_OS, 0, 3)))
    {
        // Windows
        case 'WIN':
            define('PHP_EOL', "\r\n");
            break;

        // Mac
        case 'DAR':
            define('PHP_EOL', "\r");
            break;

        // Unix
        default:
            define('PHP_EOL', "\n");
    }
}
?>
