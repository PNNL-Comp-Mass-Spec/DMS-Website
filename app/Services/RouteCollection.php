<?php
namespace App\Services;

use Closure;
use CodeIgniter\Autoloader\FileLocatorInterface;
use CodeIgniter\HTTP\Method;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Router\RouteCollectionInterface;
use Config\Modules;
use Config\Routing;
use CodeIgniter\Router\RouteCollection as BaseRouteCollection;

/*
 * Extended RouteCollection to provide additional functionality
 * Current additional functionality is providing an 'alias' capability
 * https://codeigniter.com/user_guide/extending/core_classes.html
 *
 * The functions and changes added in this class allow us to replace:
 * $routes->addPlaceholder('slashOrEnd', '/|$');
 * $routes->get('datasets(:slashOrEnd)(:any)', 'Dataset::$2');
 *
 * With:
 * $routes->getAlias('datasets', 'Dataset');
 *
 * To remove this class, the lines this class allows need to be replaced
 * in app/Config/Routes.php, and the 'routes' function in
 * app/Config/Services.php needs to also be removed.
 */
class RouteCollection extends BaseRouteCollection
{
    /**
     * Constructor
     */
    public function __construct(FileLocatorInterface $locator, Modules $moduleConfig, Routing $routing)
    {
        parent::__construct($locator, $moduleConfig, $routing);

        // Placeholder to allow a single entry for a page alias: match either a single slash or end of string.
        $this->placeholders['slashOrEnd'] = '/|$';
    }

    /**
     * Adds a single alias route to the collection.
     *
     * Example:
     *      $routes->addAlias('news', 'Posts');
     *
     * @param array|(Closure(mixed...): (ResponseInterface|string|void))|string $to
     */
    public function addAlias(string $from, $to, ?array $options = null)
    {
        // Match either just '[alias name]', or '[alias name]/[function and data?]'
        // converting to '[target class]::[function and data?]'.
        // If [function and data?] is blank, '[target class]::' just directs to
        // '[target class]::index', which is the expected behavior.
        $this->create('*', $from . '(:slashOrEnd)(:any)', $to . '::$2', $options);

        return $this;
    }

    /**
     * Specifies a single route to match for multiple HTTP Verbs.
     *
     * Example:
     *  $route->matchAlias( ['GET', 'POST'], 'users', 'users');
     *
     * @param array|(Closure(mixed...): (ResponseInterface|string|void))|string $to
     */
    public function matchAlias(array $verbs = [], string $from = '', $to = '', ?array $options = null): RouteCollectionInterface
    {
        // Match either just '[alias name]', or '[alias name]/[function and data?]'
        // converting to '[target class]::[function and data?]'.
        // If [function and data?] is blank, '[target class]::' just directs to
        // '[target class]::index', which is the expected behavior.
        $this->match($verbs, $from . '(:slashOrEnd)(:any)', $to . '::$2', $options);

        return $this;
    }

    /**
     * Specifies an alias route that is only available to GET requests.
     *
     * @param array|(Closure(mixed...): (ResponseInterface|string|void))|string $to
     */
    public function getAlias(string $from, $to, ?array $options = null): RouteCollectionInterface
    {
        // Match either just '[alias name]', or '[alias name]/[function and data?]'
        // converting to '[target class]::[function and data?]'.
        // If [function and data?] is blank, '[target class]::' just directs to
        // '[target class]::index', which is the expected behavior.
        $this->create(Method::GET, $from . '(:slashOrEnd)(:any)', $to . '::$2', $options);

        return $this;
    }
}
