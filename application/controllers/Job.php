<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job extends CI_Controller {

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
        $this->load->library('yarn_rest');
        $this->load->library('logs');
        $this->load->model('settings_model');
        date_default_timezone_set('Europe/Paris');
    }
    
	public function index($job_id=NULL, $node_id=NULL, $cont_id=NULL)
	{   
        if($job_id == NULL){
            echo $job_id;
            show_error("Rest API is not responding for this url", "2", $heading = 'An Error Was Encountered');
        }
        else{
            $this->load->view('header');

            $params = $this->settings_model->get_params();
            $data["job_infos"] = $this->yarn_rest->get_job_info($params['history_server'], $job_id);
            
            if($node_id == NULL || $cont_id == NULL){
                $data["job_attempts"] = $this->yarn_rest->get_job_attemps($params['history_server'], $job_id);
                $data["tasks_attempts"] = $this->yarn_rest->get_tasks_attempts($params['history_server'], $job_id);

                $this->load->view('job', $data);
            }else{
                $data["job_attempt_logs"] = $this->logs->get_attempt_logs($params["log_directory"], $job_id, $data["job_infos"]["user"], $node_id, $cont_id);
                $this->load->view('job_logs', $data);
            }
            
            $this->load->view('footer');  
        }
	}
}
