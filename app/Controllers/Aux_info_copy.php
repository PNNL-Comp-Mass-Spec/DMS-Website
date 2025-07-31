<?php
namespace App\Controllers;

/**
 * This class is used to copy Aux info data from one entity to another.
 * For example, on https://dms2.pnl.gov/aux_info/entry/Experiment/214930/QC_Shew_18_01
 * see the Copy Info textbox and Copy button
 */
class Aux_info_copy extends BaseController {

    protected $helpers = ['url', 'text', 'form'];

    private $model = null;

    /**
     * CodeIgniter 4 Constructor.
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //--------------------------------------------------------------------
        // Preload any models, libraries, etc, here.
        //--------------------------------------------------------------------
        // E.g.:
        // $this->session = service('session');

        $this->my_tag = "aux_info_copy";
        $this->my_title = "Aux Info Copy";

        $this->model = model('App\\Models\\M_aux_info_copy');
    }

    /**
     * Update database (from AJAX call)
     */
    function update()
    {
        $fields = $this->model->get_field_validation_fields();

        // Get expected field values from POST
        $parmObj = new \stdClass();
        foreach(array_keys($fields) as $name) {
            $parmObj->$name = isset($_POST[$name])?$_POST[$name]:'';
        }
        $command = $this->request->getPost('CopyMode');

        $message = "";
        $result = $this->model->add_or_update($parmObj, $command, $message);
        if($result != 0) {
            echo "($result):$message";
        } else {
            echo "Update was successful";
        }
    }
}
?>
