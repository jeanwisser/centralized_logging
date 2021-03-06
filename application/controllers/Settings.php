<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('settings_model');
        date_default_timezone_set('Europe/Paris');
    }
    
	public function index($set=FALSE)
	{   
        $this->load->view('header');
        
        if($set){
            $this->settings_model->save_params($this->input->post('history_server'), $this->input->post('oozie_server'),$this->input->post('spark_server'), $this->input->post('log_directory'));
        }
        
        $data['params'] = $this->settings_model->get_params();
        
        $this->load->view('settings', $data);
        
        $this->load->view('footer');
	}
}
