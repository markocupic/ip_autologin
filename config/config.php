<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Created by PhpStorm.
 * User: Marko
 * Date: 12.08.2017
 * Time: 12:27
 */
if(TL_MODE == 'FE'){
    $GLOBALS['TL_HOOKS']['generatePage'][] = array('IpAutologin', 'loginUser');
    $GLOBALS['TL_HOOKS']['checkCredentials'][] = array('IpAutologin', 'checkCredentials');
}
?>