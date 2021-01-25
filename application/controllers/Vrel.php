<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vrel extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		
		$this->load->model(array('release_model','bom_model', 'main_model'));

		$this->data['siteTitle'] = $this->config->item('site_title');

		

	}

	public function _remap($method, $params = array())
	{
		if($this->input->is_ajax_request()){
            if( method_exists($this, $method) ){
                call_user_func_array(array($this,$method), $params);
            }
        }else{ //ajax가 아니면
			
			if (method_exists($this, $method)) {

				$user_id = $this->session->userdata('user_id');
				if(isset($user_id) && $user_id != ""){
					
					$this->load->view('/layout/header_rel',$this->data);
					call_user_func_array(array($this,$method), $params);
					$this->load->view('/layout/tail');

				}else{

					alert('로그인이 필요합니다.',base_url('register/login'));

				}

            } else {
                show_404();
            }

        }
		
	}

	public function rsmt()
	{

		$data['title'] = "[SMT]생산현황판";
		$params['GJ_GB'] = "SMT";

		$data['leftData'] = $this->release_model->get_left_data($params);
		$data['toDate'] = $this->release_model->get_todate_num();
		$data['viewList'] = $this->release_model->get_rel_view($params);


		return $this->load->view('/release/rel_viewsmt',$data);
	}
	public function rass()
	{

		$data['title'] = "[조립]생산현황판";
		$params['GJ_GB'] = "ASS";
		
		$data['leftData'] = $this->release_model->get_left_data($params);
		$data['toDate'] = $this->release_model->get_todate_num();
		$data['viewList'] = $this->release_model->get_rel_view($params);


		return $this->load->view('/release/rel_viewass',$data);
	}


}