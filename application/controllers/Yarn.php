<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Yarn extends CI_Controller {

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
        $this->load->library('pagination');
        $this->load->library('yarn_rest');
        $this->load->model('settings_model');
        date_default_timezone_set('Europe/Paris');
    }
    
	public function index($page=1)
	{
        /* number of jobs per page */
        $per_page = 50;
        $params = $this->settings_model->get_params();
        $data["params"] = $params;
        $nb_jobs = count($this->yarn_rest->get_jobs($params["history_server"]));
        
        if($page <= 0 || $page > ceil($nb_jobs/$per_page)){
            show_404($page = '', $log_error = TRUE);
            
        }else{
            
            $data["jobs"] = $this->yarn_rest->get_jobs($params["history_server"]);
            $data["nb_jobs"] = $nb_jobs;
            $data["position"] = ($page*$per_page)-50;
            $data["per_page"] = $per_page;

            /* pagination configuration */
            $config['base_url'] = base_url("/yarn/index");;
            $config['total_rows'] = $nb_jobs;
            $config['per_page'] = $per_page;
            $config['use_page_numbers'] = TRUE;
            $config['cur_tag_open'] = '<li class="active">';
            $config['cur_tag_close'] = '</li>';
            $config['num_tag_open'] = '<li class="waves-effect">';
            $config['num_tag_close'] = '</li>';
            $config['next_link'] = '<i class="material-icons">chevron_right</i>';
            $config['next_tag_open'] = '<li class="waves-effect">';
            $config['next_tag_close'] = '</li>';
            $config['prev_link'] = '<i class="material-icons">chevron_left</i>';
            $config['prev_tag_open'] = '<li class="waves-effect">';
            $config['prev_tag_close'] = '</li>';

            $config['last_link'] = 'Last';
            $config['last_tag_open'] = '<li class="waves-effect">';
            $config['last_tag_close'] = '</li>';

            $config['first_link'] = 'First';
            $config['first_tag_open'] = '<li class="waves-effect">';
            $config['first_tag_close'] = '</li>';


            $this->pagination->initialize($config);

            $data["pagination"] = $this->pagination->create_links();

            $this->load->view('header');
            $this->load->view('yarn', $data);
            $this->load->view('footer');
            
        }
	}
}
