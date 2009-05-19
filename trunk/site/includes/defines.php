<?php
define('_COOPERATION_IMAGE_MAX_WIDTH_', 1024);
define('_COOPERATION_IMAGE_MAX_HEIGHT_', 768);

define('_FACILITY_IMAGE_MAX_WIDTH_', 1024);
define('_FACILITY_IMAGE_MAX_HEIGHT_', 768);

define('_PROJECT_IMAGE_MAX_WIDTH_', 1024);
define('_PROJECT_IMAGE_MAX_HEIGHT_', 768);

define('_MEMBER_IMAGE_MAX_WIDTH_', 1024);
define('_MEMBER_IMAGE_MAX_HEIGHT_', 768);

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