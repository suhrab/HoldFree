<?php
	#AUTOGENERATED BY HYBRIDAUTH 2.1.2 INSTALLER - Wednesday 10th of April 2013 11:17:18 PM

/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

return
	array(
		"base_url" => "http://holdfree.com/class/hybridauth/",

		"providers" => array ( 
			// openid providers

			"Google" => array ( 
				"enabled" => true,
				"keys"    => array ( "id" => "247515410039.apps.googleusercontent.com", "secret" => "DeHVmZkx2cCGXvY5wrXfYQHT" ),
                "scope" => "https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email"
			),

			"Facebook" => array ( 
				"enabled" => true,
				"keys"    => array ( "id" => "151620868368392", "secret" => "dcfff7e7483b1c442a5ef1b23a21f528" ),
                "scope" => "email, user_location, user_hometown"
			),

            "Vkontakte" => array (
                "enabled" => true,
                "keys"    => array ( "id" => "3817938", "secret" => "8VuCwFEXuGoKCpPoL0xX" )
            ),
		),

		// if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
		"debug_mode" => false,

		"debug_file" => ""
	);
