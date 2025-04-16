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
	 * @param bool                 $returnObject True to return an object instead of a string
	 * @param IncomingRequest|null $request      A request to use when retrieving the path
	 *
	 * @return string|URI
	 */
	function current_uri(bool $returnObject = false, IncomingRequest $request = null)
	{
		$request = $request ?? Services::request();
		$uri     = $request->getUri();

		// NOTE: May need to call rawurldecode() on the returned value to replace HTML-encoded characters. wildcard_conversion_helper's decode_special_values also does this.
		return $returnObject ? $uri : URI::createURIString($uri->getScheme(), $uri->getAuthority(), $uri->getPath());
	}
}

if (! function_exists('decodeSegments'))
{
	/**
	 * Performs URL Decoding on all entries in $segments, returning them in a new array
	 *
	 * @param array  $segments     URI segments, usually from URI->getSegments()
	 *
	 * @return string|URI
	 */
	function decodeSegments(array $segments)
	{
        helper('wildcard_conversion');
        $outSegments = array();

        foreach ($segments as $i => $value)
        {
            $outSegments[$i] = decode_special_values($value);
        }

		return $outSegments;
	}
}

if (! function_exists('getCurrentUriDecodedSegments'))
{
	/**
	 * Performs URL Decoding on all entries in current_uri(true)->getSegments, returning them in an array
	 *
	 * @return array
	 */
	function getCurrentUriDecodedSegments()
	{
        $uri = current_uri(true);
        $segments = $uri->getSegments();

		return decodeSegments($segments);
	}
}
