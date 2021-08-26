<?php

namespace Config;

use App\Services\RouteCollection;
use CodeIgniter\Config\BaseService;
use CodeIgniter\Config\Services as AppServices;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
	// public static function example($getShared = true)
	// {
	//     if ($getShared)
	//     {
	//         return static::getSharedInstance('example');
	//     }
	//
	//     return new \CodeIgniter\Example();
	// }

	//--------------------------------------------------------------------

	/**
	 * The Routes service is a class that allows for easily building
	 * a collection of routes.
	 *
	 * @param boolean $getShared
	 *
	 * @return RouteCollection
	 */
	public static function routes(bool $getShared = true)
	{
		if ($getShared)
		{
			return static::getSharedInstance('routes');
		}

		return new RouteCollection(AppServices::locator(), config('Modules'));
	}

	public static function xss_security($getShared = true)
	{
		if ($getShared)
		{
			return static::getSharedInstance('xss_security');
		}

		return new \App\Services\XssSecurity();
	}
}
