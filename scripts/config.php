<?php
/**
 * Bradley Seyler, Aaron Reynolds, Christian Talmadge
 * 6/7/2019
 * config.php
 *
 * This file contains data for linkedin integration
 */

$baseURL = 'http://smallerthree.greenriverdev.com';				// base or site url is the site where code 																		linked in button is kept for signup
$callbackURL = 'http://smallerthree.greenriverdev.com/Register_LinkedIn';	//callback or redirect url is the page you 																	want to open after successful getting of data
//i.e. index.php page (must be same in linkedin dashboard)
$linkedinApiKey = '86af6ozdsw3g0m';								//APP ID(will receive from linkedin dashboard)
$linkedinApiSecret = 'RddKQ4FGsuQD3b3b';						//APP Client
$linkedinScope = 'r_liteprofile r_emailaddress';				//This is fixed no need to change
