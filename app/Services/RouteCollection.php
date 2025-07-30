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

    /**
     * Add the API/resource route set for a controller to the collection. This is a wrapper around '->resource' with URI 'api/[controller]' and 'api_' prefixes for methods.
     *
     * Example:
     *      $routes->addApiRoutes('requested_run');
     *
     * @param string $name controller name used in URI 
     */
    public function addApiRoutes(string $name, ?array $options = null)
    {
        if (!isset($options['websafe']))
        {
            $options['websafe'] = 1;
        }

        $this->resourcePrefixed($name, 'api', $options);

        return $this;
    }

    /**
     * Copied with modifications from framework\system\Router\RouteCollection::resource
     * Creates a collections of HTTP-verb based routes for a controller, with a URI path prefix and method prefixes
     *
     * Possible Options:
     *      'controller'    - Customize the name of the controller used in the 'to' route
     *      'placeholder'   - The regex used by the Router. Defaults to '(:any)'
     *      'websafe'   -   - '1' if only GET and POST HTTP verbs are supported
     *
     * Example:
     *
     *      $route->resource('photos');
     *
     *      // Generates the following routes:
     *      HTTP Verb | Path        | Action        | Used for...
     *      ----------+-------------+---------------+-----------------
     *      GET         /photos             index           an array of photo objects
     *      GET         /photos/new         new             an empty photo object, with default properties
     *      GET         /photos/{id}/edit   edit            a specific photo object, editable properties
     *      GET         /photos/{id}        show            a specific photo object, all properties
     *      POST        /photos             create          a new photo object, to add to the resource
     *      DELETE      /photos/{id}        delete          deletes the specified photo object
     *      PUT/PATCH   /photos/{id}        update          replacement properties for existing photo
     *
     *  If 'websafe' option is present, the following paths are also available:
     *
     *      POST        /photos/{id}/delete delete
     *      POST        /photos/{id}        update
     *
     * @param string     $name    The name of the resource/controller to route to.
     * @param string     $prefix  The prefix added to the segments (before the name, '/' added at end) and to the method name ('_' added at end)
     * @param array|null $options An list of possible ways to customize the routing.
     *
     * @return RouteCollectionInterface
     */
    public function resourcePrefixed(string $name, string $prefix = "", array $options = null): RouteCollectionInterface
    {
        // In order to allow customization of the route the
        // resources are sent to, we need to have a new name
        // to store the values in.
        $newName = implode('\\', array_map('ucfirst', explode('/', $name)));
        // If a new controller is specified, then we replace the
        // $name value with the name of the new controller.
        if (isset($options['controller']))
        {
            $newName = ucfirst(filter_var($options['controller'], FILTER_SANITIZE_STRING));
        }

        $uriPrefix = $prefix . '/';
        $methodPrefix = $prefix . '_';
        if (empty($prefix) || empty(trim($prefix)))
        {
            $uriPrefix = '';
            $methodPrefix = '';
        }

        // In order to allow customization of allowed id values
        // we need someplace to store them.
        $id = $this->placeholders[$this->defaultPlaceholder] ?? '(:segment)';

        if (isset($options['placeholder']))
        {
            $id = $options['placeholder'];
        }

        // Make sure we capture back-references
        $id = '(' . trim($id, '()') . ')';

        $methods = isset($options['only']) ? (is_string($options['only']) ? explode(',', $options['only']) : $options['only']) : ['index', 'show', 'create', 'update', 'delete', 'new', 'edit'];

        if (isset($options['except']))
        {
            $options['except'] = is_array($options['except']) ? $options['except'] : explode(',', $options['except']);
            foreach ($methods as $i => $method)
            {
                if (in_array($method, $options['except'], true))
                {
                    unset($methods[$i]);
                }
            }
        }

        if (in_array('index', $methods, true))
        {
            $this->get($uriPrefix . $name, $newName . '::' . $methodPrefix . 'index', $options);
        }
        if (in_array('new', $methods, true))
        {
            $this->get($uriPrefix . $name . '/new', $newName . '::' . $methodPrefix . 'new', $options);
        }
        if (in_array('edit', $methods, true))
        {
            $this->get($uriPrefix . $name . '/' . $id . '/edit', $newName . '::' . $methodPrefix . 'edit/$1', $options);
        }
        if (in_array('show', $methods, true))
        {
            $this->get($uriPrefix . $name . '/' . $id, $newName . '::' . $methodPrefix . 'show/$1', $options);
        }
        if (in_array('create', $methods, true))
        {
            $this->post($uriPrefix . $name, $newName . '::' . $methodPrefix . 'create', $options);
        }
        if (in_array('update', $methods, true))
        {
            $this->put($uriPrefix . $name . '/' . $id, $newName . '::' . $methodPrefix . 'update/$1', $options);
            $this->patch($uriPrefix . $name . '/' . $id, $newName . '::' . $methodPrefix . 'update/$1', $options);
        }
        if (in_array('delete', $methods, true))
        {
            $this->delete($uriPrefix . $name . '/' . $id, $newName . '::' . $methodPrefix . 'delete/$1', $options);
        }

        // Web Safe? delete needs checking before update because of method name
        if (isset($options['websafe']))
        {
            if (in_array('delete', $methods, true))
            {
                $this->post($uriPrefix . $name . '/' . $id . '/delete', $newName . '::' . $methodPrefix . 'delete/$1', $options);
            }
            if (in_array('update', $methods, true))
            {
                $this->post($uriPrefix . $name . '/' . $id, $newName . '::' . $methodPrefix . 'update/$1', $options);
            }
        }

        return $this;
    }
}
