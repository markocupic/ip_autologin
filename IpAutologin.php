<?php if (!defined('TL_ROOT'))
{
    die('You cannot access this file directly!');
}

/**
 * Class IpAutologin
 * Autologin Frontend User if the remote Address is assigned to
 * The IP-Address has to be registered in tl_member
 * @author Marko Cupic <m.cupic@gmx.ch>
 */
class IpAutologin extends \Frontend
{
    /**
     * @var Environment|null
     */
    protected $Environment = null;

    /**
     * @var null|string
     */
    protected $ip = null;

    /**
     * @var mixed|null
     */
    protected $autologinUsername = null;

    /**
     * IpAutologin constructor.
     */
    public function __construct()
    {
        $this->import('Database');
        $this->Environment = Environment::getInstance();
        $this->ip = $this->Environment->ip;
        $skip = false;
        $objMember = $this->Database->prepare("SELECT * FROM tl_member WHERE disable=? AND enableIpAutologin=?")->execute('', '1');
        while ($objMember->next() && !$skip)
        {
            // Find first occurence of the current remote address
            $arr1 = deserialize($objMember->ipAutologinAddresses, true);
            foreach ($arr1 as $k1 => $v1)
            {
                if (isset($v1['ipAutologinAddressItem']))
                {
                    if ($v1['ipAutologinAddressItem'] != '')
                    {
                        if ($v1['ipAutologinAddressItem'] == $this->ip)
                        {
                            $this->autologinUsername = $objMember->username;
                            $skip = true;
                            break;
                        }
                    }
                }
            }
        }


        parent::__construct();
    }


    /**
     * @param $objPage
     * @param $objLayout
     * @param $objPageRegular
     */
    public function loginUser($objPage, $objLayout, $objPageRegular)
    {

        if (!FE_USER_LOGGED_IN && $this->autologinUsername !== null)
        {
            // Check if user exists
            $objMember = $this->Database->prepare("SELECT * FROM tl_member WHERE username=? AND disable=? AND enableIpAutologin=?")->execute($this->autologinUsername, '', '1');
            if (!$objMember->numRows)
            {
                return;
            }

            $this->import('FrontendUser', 'User');
            $this->Input->setPost('username', $this->autologinUsername);
            $this->Input->setPost('password', 'xxxyyyyyyyyyy');

            $this->import('FrontendUser', 'User');
            $strRedirect = $this->Environment->request;


            // Overwrite the jumpTo page with an individual group setting
            $objGroup = $this->Database->prepare("SELECT groups FROM tl_member WHERE username=?")
                ->limit(1)
                ->execute($this->Input->post('username'));

            if ($objGroup->numRows)
            {
                $arrGroups = deserialize($objGroup->groups);

                if (is_array($arrGroups) && !empty($arrGroups))
                {
                    $time = time();

                    // Get the first active jumpTo page
                    $objGroupPage = $this->Database->prepare("SELECT p.id, p.alias FROM tl_member_group g LEFT JOIN tl_page p ON g.jumpTo=p.id WHERE g.id IN(" . implode(',', array_map('intval', $arrGroups)) . ") AND g.jumpTo>0 AND g.redirect=1 AND g.disable!=1 AND (g.start='' OR g.start<$time) AND (g.stop='' OR g.stop>$time) AND p.published=1 AND (p.start='' OR p.start<$time) AND (p.stop='' OR p.stop>$time) ORDER BY " . $this->Database->findInSet('g.id', $arrGroups))
                        ->limit(1)
                        ->execute();

                    if ($objGroupPage->numRows)
                    {
                        $strRedirect = $this->generateFrontendUrl($objGroupPage->row());
                    }
                }
            }


            // Login and redirect
            if ($this->User->login())
            {
                $this->redirect($strRedirect);
            }

            $this->reload();
        }

    }

    /**
     * @param $strUsername
     * @param $strPassword
     * @return bool
     */
    public function checkCredentials($strUsername, $strPassword)
    {
        // Authenticate without password
        if ($this->autologinUsername !== null)
        {
            if ($this->autologinUsername == $strUsername)
            {
                return true;
            }
        }

        return false;
    }
}

?>