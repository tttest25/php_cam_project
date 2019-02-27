<?php
/**
 * Camera php
 *
 * In normal usage, this script simply redirects to doku.php. However it can also be used as a routing
 * script with PHP's builtin webserver. It takes care of .htaccess compatible rewriting, directory/file
 * access permission checking and passing on static files.
 *
 * Usage example:
 *
 *   php -S localhost:8000 index.php
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Andreas Gohr <andi@splitbrain.org>
 */


// Comment these lines to hide errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'includes/config.php';
require 'includes/functions.php';
init();

#404
