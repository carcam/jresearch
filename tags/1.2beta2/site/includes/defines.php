<?php
define('_MEMBER_IMAGE_MAX_WIDTH_', 1024);
define('_MEMBER_IMAGE_MAX_HEIGHT_', 768);

define('_JRESEARCH_VERSION_', '1.2.0');
define('_JRESEARCH_BIBUTILS_VERSION_', '3.41');

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