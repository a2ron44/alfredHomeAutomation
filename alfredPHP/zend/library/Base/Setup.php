<?php
class Base_Setup {
	
	//session namespaces
	const SESSION_ACL = 'base_Auth';
	const SESSION_MENU = 'base_menu';
	const SESSION_LASTREQUEST = 'lastRequest_base';
	
	const COOKIE_REMEMBER = 'baseRemember';
	const COOKIE_REMEMBER_USER = 'baseRememberUser';
	
	//name to use on db saves for ctrl user fields
	const DB_SYS_USERNAME = 'SYS';

	//may have to alert default db tables if changing
	const ROLE_BASE =  'AA_ANY';  //lowest level role
	const ROLE_LOGGED_IN_BASE = 'AB_ALL';
	const ROLE_GUEST = 'GUEST';	//role for guest
	const ROLE_SUPER = 'SUPER';  // superuser, all ACL access given
	
	//site specific
	const SITE_EMAIL_SUPPORT = 'support@site.com';
	const SITE_HEAD_TITLE = 'BaseApp';
	//menu
	const DEFAULT_LINK_IMAGE = 'star.png';
}