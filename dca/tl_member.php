<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 16.08.2017
 * Time: 21:24
 */

// Palettes
$GLOBALS['TL_DCA']['tl_member']['palettes']['__selector__'][] = 'enableIpAutologin';
$GLOBALS['TL_DCA']['tl_member']['subpalettes']['enableIpAutologin'] = 'ipAutologinAddresses';
$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('{login_legend', '{ip_autologin_legend},enableIpAutologin;{login_legend', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);





// Fields
$GLOBALS['TL_DCA']['tl_member']['fields']['enableIpAutologin'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_member']['enableIpAutologin'],
    'exclude' => true,
    'filter' => true,
    'inputType' => 'checkbox',
    'eval' => array('submitOnChange' => true),
    'sql' => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['ipAutologinAddresses'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_member']['ipAutologinAddresses'],
    'exclude' => true,
    'inputType' => 'multiColumnWizard',
    'eval' => array
    (
        'columnFields' => array
        (
            'ipAutologinAddressItem' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_member']['ipAutologinAddressItem'],
                'exclude' => true,
                'inputType' => 'text',
                'eval' => array('style' => 'width:220px')
            )
        )
    ),
    'sql' => "blob NULL"
);
