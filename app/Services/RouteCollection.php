<?php
namespace App\Services;

use CodeIgniter\Autoloader\FileLocator;
use CodeIgniter\Router\RouteCollectionInterface;
use Config\Modules;
use CodeIgniter\Router\RouteCollection as BaseRouteCollection;

/*
 * Extended RouteCollection to provide additional functionality
 * Current additional functionality is providing an 'alias' capability
 * https://codeigniter.com/user_guide/extending/core_classes.html
 *
 * The functions and changes added in this class allow us to replace:
 * $routes->addPlaceholder('slashOrEnd', '/|$');
 * $routes->get('biomaterial(:slashOrEnd)(:any)', 'Cell_culture::$2');
 *
 * With:
 * $routes->getAlias('biomaterial', 'Cell_culture');
 *
 * To remove this class, the lines this class allows need to be replaced
 * in app/Config/Routes.php, and the 'routes' function in 
 * app/Config/Services.php needs to also be removed.
 */
class RouteCollection extends BaseRouteCollection
{
	/**
	 * Constructor
	 *
	 * @param FileLocator $locator
	 * @param Modules     $moduleConfig
	 */
    public function __construct(FileLocator $locator, Modules $moduleConfig)
    {
        parent::__construct($locator, $moduleConfig);

        $this->placeholders['slashOrEnd'] = '/|$';
    }

	//--------------------------------------------------------------------
	/**
	 * Specifies a single alias route to match for multiple HTTP Verbs.
	 *
	 * Example:
	 *  $route->matchAlias( ['get', 'post'], 'user', 'users');
	 *
	 * @param array        $verbs
	 * @param string       $from
	 * @param string|array $to
	 * @param array|null   $options
	 *
	 * @return RouteCollectionInterface
	 */
    public function matchAlias(array $verbs = [], string $from = '', $to = '', array $options = null): RouteCollectionInterface
    {
        $this->match($verbs, $from . '(:slashOrEnd)(:any)', $to . '::$2', $options);

        return $this;
    }

	//--------------------------------------------------------------------
	/**
	 * Specifies an alias route that is only available to GET requests.
	 *
	 * Example:
	 *      $routes->getAlias('news', 'Posts');
	 *
	 * @param string       $from
	 * @param array|string $to
	 * @param array|null   $options
	 *
	 * @return RouteCollectionInterface
	 */
    public function getAlias(string $from, $to, array $options = null): RouteCollectionInterface
    {
        $this->create('get', $from . '(:slashOrEnd)(:any)', $to . '::$2', $options);

        return $this;
    }

	//--------------------------------------------------------------------
	/**
	 * Adds a single alias route to the collection.
	 *
	 * Example:
	 *      $routes->addAlias('news', 'Posts');
	 *
	 * @param string       $from
	 * @param array|string $to
	 * @param array|null   $options
	 *
	 * @return RouteCollectionInterface
	 */
    public function addAlias(string $from, $to, array $options = null)
    {
        $this->create('*', $from . '(:slashOrEnd)(:any)', $to . '::$2', $options);

        return $this;
    }
}
