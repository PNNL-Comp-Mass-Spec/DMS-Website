<?php
namespace App\Controllers;

class Preferences extends BaseController {
    function __construct()
    {
        $this->my_model = 'Dms_preferences';
        $this->helpers = array_merge($this->helpers, ['url', 'text', 'dms_search', 'cookie', 'user']);
    }

    /**
     * CodeIgniter 4 Constructor.
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        session_start();

        $this->model = model('App\\Models\\'.$this->my_model);
    }

    // --------------------------------------------------------------------
    function index()
    {
        $this->set('', '');
        return;
    }

    // --------------------------------------------------------------------
    function set($param, $value)
    {
        helper('menu');
        $data['title'] = 'User Preferences';
        $data['heading'] = $data['title'];

        // nav_bar setup
        $this->menu = model('App\Models\Dms_menu');
        $data['nav_bar_menu_items']= get_nav_bar_menu_items('Preferences', $this);

        $result = '';
        if($param != '' && $value != '') {
            $result = $this->model->set_preference($param, $value);
        }
        $data['settings'] = $this->model->get_preferences();

        $data['result'] = $result;
        echo view('special/preferences', $data);

    }

    // --------------------------------------------------------------------
    function clear()
    {
        $this->model->clear_saved_defaults();
        $this->set('', '');
        return;
    }

    // --------------------------------------------------------------------
    function session()
    {
        $clear_base_url = site_url("preferences/clear_session");
        echo "<a href='".site_url()."'>Home</a><br>";
        echo "<a href='".site_url("preferences")."'>Preferences</a><br>";
        echo "<hr />";
        echo "Session ID: ". session_id  () . "<hr />";
        echo "SID: ". SID . "<hr />";

        foreach($_SESSION as $k => $v) {
            echo $k . " <a href='$clear_base_url/$k'>clear</a>". "<br />" . serialize($v) . "<hr />";
        }
    }
    // --------------------------------------------------------------------
    function clear_session($key)
    {
        if(isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
        redirect()->to(site_url('preferences/session'));
    }
    // --------------------------------------------------------------------
    function columns()
    {
        helper(['url']);
        $segs = decodeSegments(array_slice($this->request->uri->getSegments(), 2));
        $tag = array_shift($segs);
        $name = "display_cols_".$tag;

        // if no columns are specified on the url,
        // clear any previous saved version to revert
        // to the default
        if(count($segs)==0) {
            unset($_SESSION[$name]);
            echo 'cleared';
            return;
        }

        // wrap any column names that contain spaces
        // with appropriate quotes
        for($i=0;$i<count($segs);$i++) {
            if(preg_match("/\s/", $segs[$i])) {
                $segs[$i] = "[$segs[$i]]";
            }
        }

        // generate the column list
        $value = implode(', ', $segs);
        echo $value;
        $_SESSION[$name] = $value;
    }
}
?>
