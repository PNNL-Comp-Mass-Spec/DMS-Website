<?php

/**
 * Additional URL Helper methods to load with the CodeIgniter URL Helper
 */

use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\URI;
use Config\Services;

/**
 * Additional URL Helpers
 */

//--------------------------------------------------------------------

if (! function_exists('current_uri'))
{
	/**
	 * Returns the path part of the current URL based on the IncomingRequest.
	 * Compared to 'current_url', this does not include everything after base_url()
	 * String returns ignore query and fragment parts.
	 *
	 * @param boolean              $returnObject True to return an object instead of a string
	 * @param IncomingRequest|null $request      A request to use when retrieving the path
	 *
	 * @return string|URI
	 */
	function current_uri(bool $returnObject = false, IncomingRequest $request = null)
	{
		$request = $request ?? Services::request();
		$uri     = $request->getUri();

		return $returnObject ? $uri : URI::createURIString($uri->getScheme(), $uri->getAuthority(), $uri->getPath());
	}
}
