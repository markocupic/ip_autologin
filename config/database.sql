-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

--
-- Table `tl_member`
--

CREATE TABLE `tl_member` (
  `enableIpAutologin` char(1) NOT NULL default '',
  `ipAutologinAddresses` blob NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;