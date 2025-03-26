<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Session\Handlers\FileHandler;

class App extends BaseConfig
{
    // --------------------------- BEGIN DMS Customizations--------------------

    public $pwiki = "";

    public $wikiHelpLinkPrefix = 'DMS_Help_for_';

    public $version_color_code = 'black';
    public $version_banner = NULL;
    public $version_label = 'Production';

    public $inhibit_sproc_call = FALSE;
    public $sproc_call_log_enabled = FALSE;

    public $modify_config_db_enabled = FALSE;

    public $file_attachment_archive_root_path = "/mnt/dms_attachments/";

    public $file_attachment_local_root_path = "/files2/dms_attachments/";

    // Path relative to index.php, which is inside the 'public' folder.
    public $model_config_path = "./model_config/";

    // Path to directory containing model_config DBs or flag files specific to the site instance
    // Files located here override those in the path specified by $model_config_path
    // Path relative to index.php, which is inside the 'public' folder; empty string (default) means there are no overrides.
    public $model_config_instance_path = "";

    public $dms_inst_source_url = "http://gigasax.pnl.gov";

    public $page_menu_root = NULL;

    // Include trailing '/', if provided
    private $baseURLPrefix = '';

    // On PrismWeb2 at /files1/www/html/dmsdev_pg
    // private $baseURLPrefix = '/dmsdev-pg/';

    // On PrismWeb2 at /files1/www/html/dmsdev2
    // private $baseURLPrefix = '';

    // On PrismWeb3 at /files1/www/html/prismsupport/dms-pg/app/Config/App.php
    // private $baseURLPrefix = '/dmspg/';

    // --------------------------- END DMS Customizations----------------------

    // --------------------------------------------------------------------
    function __construct()
    {
        // --------------------------- BEGIN DMS Customizations--------------------
        // Need to set the properties before we call the parent constructor
        $serverHttpsState = \Config\Services::superglobals()->server("HTTPS");
        $protocol = isset($serverHttpsState) && $serverHttpsState == "on" ? "https" : "http";
        $serverName = \Config\Services::superglobals()->server("SERVER_NAME");
        $this->baseURL = "{$protocol}://".$serverName.$this->baseURLPrefix;
        $this->uriProtocol = 'PATH_INFO';
        $this->appTimezone = 'America/Los_Angeles';

        // Is the user accessing DMS from bionet?
        $server_bionet = stripos($serverName, ".bionet") !== FALSE;

        if ($server_bionet) {
            $this->pwiki = 'http://prismwiki.bionet/wiki/';
        }
        else {
            $this->pwiki = 'https://prismwiki.pnl.gov/wiki/';
        }
        // --------------------------- END DMS Customizations----------------------

        // Call the parent constructor
        parent::__construct();

        // --------------------------- BEGIN DMS Customizations--------------------
        ConfigFromEnvironmentFile::getConfigFromEnvironmentSpecificFile($this);
        // --------------------------- END DMS Customizations----------------------
    }

    /**
     * --------------------------------------------------------------------------
     * Base Site URL
     * --------------------------------------------------------------------------
     *
     * URL to your CodeIgniter root. Typically, this will be your base URL,
     * WITH a trailing slash:
     *
     * E.g., http://example.com/
     */
    public string $baseURL = 'http://localhost:8080/';

    /**
     * Allowed Hostnames in the Site URL other than the hostname in the baseURL.
     * If you want to accept multiple Hostnames, set this.
     *
     * E.g.,
     * When your site URL ($baseURL) is 'http://example.com/', and your site
     * also accepts 'http://media.example.com/' and 'http://accounts.example.com/':
     *     ['media.example.com', 'accounts.example.com']
     *
     * @var list<string>
     */
    public array $allowedHostnames = [];

    /**
     * --------------------------------------------------------------------------
     * Index File
     * --------------------------------------------------------------------------
     *
     * Typically, this will be your `index.php` file, unless you've renamed it to
     * something else. If you have configured your web server to remove this file
     * from your site URIs, set this variable to an empty string.
     */
	public string $indexPage = '';

    /**
     * --------------------------------------------------------------------------
     * URI PROTOCOL
     * --------------------------------------------------------------------------
     *
     * This item determines which server global should be used to retrieve the
     * URI string. The default setting of 'REQUEST_URI' works for most servers.
     * If your links do not seem to work, try one of the other delicious flavors:
     *
     *  'REQUEST_URI': Uses $_SERVER['REQUEST_URI']
     * 'QUERY_STRING': Uses $_SERVER['QUERY_STRING']
     *    'PATH_INFO': Uses $_SERVER['PATH_INFO']
     *
     * WARNING: If you set this to 'PATH_INFO', URIs will always be URL-decoded!
     */
    public string $uriProtocol = 'REQUEST_URI';

    /*
    |--------------------------------------------------------------------------
    | Allowed URL Characters
    |--------------------------------------------------------------------------
    |
    | This lets you specify which characters are permitted within your URLs.
    | When someone tries to submit a URL with disallowed characters they will
    | get a warning message.
    |
    | As a security measure you are STRONGLY encouraged to restrict URLs to
    | as few characters as possible.
    |
    | By default, only these are allowed: `a-z 0-9~%.:_-`
    |
    | Set an empty string to allow all characters -- but only if you are insane.
    |
    | The configured value is actually a regular expression character group
    | and it will be used as: '/\A[<permittedURIChars>]+\z/iu'
    |
    | DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
    |
    */
    public string $permittedURIChars = 'a-z 0-9~%.:_,\-';

    /**
     * --------------------------------------------------------------------------
     * Default Locale
     * --------------------------------------------------------------------------
     *
     * The Locale roughly represents the language and location that your visitor
     * is viewing the site from. It affects the language strings and other
     * strings (like currency markers, numbers, etc), that your program
     * should run under for this request.
     */
    public string $defaultLocale = 'en';

    /**
     * --------------------------------------------------------------------------
     * Negotiate Locale
     * --------------------------------------------------------------------------
     *
     * If true, the current Request object will automatically determine the
     * language to use based on the value of the Accept-Language header.
     *
     * If false, no automatic detection will be performed.
     */
    public bool $negotiateLocale = false;

    /**
     * --------------------------------------------------------------------------
     * Supported Locales
     * --------------------------------------------------------------------------
     *
     * If $negotiateLocale is true, this array lists the locales supported
     * by the application in descending order of priority. If no match is
     * found, the first locale will be used.
     *
     * IncomingRequest::setLocale() also uses this list.
     *
     * @var list<string>
     */
    public array $supportedLocales = ['en'];

    /**
     * --------------------------------------------------------------------------
     * Application Timezone
     * --------------------------------------------------------------------------
     *
     * The default timezone that will be used in your application to display
     * dates with the date helper, and can be retrieved through app_timezone()
     *
     * @see https://www.php.net/manual/en/timezones.php for list of timezones
     *      supported by PHP.
     */
    public string $appTimezone = 'UTC';

    /**
     * --------------------------------------------------------------------------
     * Default Character Set
     * --------------------------------------------------------------------------
     *
     * This determines which character set is used by default in various methods
     * that require a character set to be provided.
     *
     * @see http://php.net/htmlspecialchars for a list of supported charsets.
     */
    public string $charset = 'UTF-8';

    /**
     * --------------------------------------------------------------------------
     * Force Global Secure Requests
     * --------------------------------------------------------------------------
     *
     * If true, this will force every request made to this application to be
     * made via a secure connection (HTTPS). If the incoming request is not
     * secure, the user will be redirected to a secure version of the page
     * and the HTTP Strict Transport Security (HSTS) header will be set.
     */
    public bool $forceGlobalSecureRequests = false;

    /**
     * --------------------------------------------------------------------------
     * Reverse Proxy IPs
     * --------------------------------------------------------------------------
     *
     * If your server is behind a reverse proxy, you must whitelist the proxy
     * IP addresses from which CodeIgniter should trust headers such as
     * X-Forwarded-For or Client-IP in order to properly identify
     * the visitor's IP address.
     *
     * You need to set a proxy IP address or IP address with subnets and
     * the HTTP header for the client IP address.
     *
     * Here are some examples:
     *     [
     *         '10.0.1.200'     => 'X-Forwarded-For',
     *         '192.168.5.0/24' => 'X-Real-IP',
     *     ]
     *
     * @var array<string, string>
     */
    public array $proxyIPs = [];

    /**
     * --------------------------------------------------------------------------
     * Content Security Policy
     * --------------------------------------------------------------------------
     *
     * Enables the Response's Content Secure Policy to restrict the sources that
     * can be used for images, scripts, CSS files, audio, video, etc. If enabled,
     * the Response object will populate default values for the policy from the
     * `ContentSecurityPolicy.php` file. Controllers can always add to those
     * restrictions at run time.
     *
     * For a better understanding of CSP, see these documents:
     *
     * @see http://www.html5rocks.com/en/tutorials/security/content-security-policy/
     * @see http://www.w3.org/TR/CSP/
     */
    public bool $CSPEnabled = false;
}
