<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PLN extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		
		$this->load->model(array('act_model','main_model'));

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
					
					$this->load->view('/layout/header',$this->data);
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

    public function index()
	{
		$data['title'] = "생산계획등록";
		
		$prefs = array(
				'start_day'    => 'sunday',
				'month_type'   => 'short',
				'day_type'     => 'short',
				'show_next_prev'  => true,
				'show_other_days' => false,
				'next_prev_url'   => base_url('PLN/index/')
		);

		$year = (!empty($this->uri->segment(3)))?$this->uri->segment(3):date("Y",time());
		$month = (!empty($this->uri->segment(4)))?$this->uri->segment(4):date("m",time());

		

		$prefs['template'] = '

				{table_open}<table border="0" cellpadding="0" cellspacing="3" id="calendar">{/table_open}

				{heading_row_start}<tr class="headset">{/heading_row_start}

				{heading_previous_cell}
				<th>
					<a href="{previous_url}" class="moveBtn">이전달</a>
				</th>
				{/heading_previous_cell}

				{heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
				
				{heading_next_cell}
				<th>
					<a href="{next_url}" class="moveBtn">다음달</a>
				</th>
				{/heading_next_cell}

				{heading_row_end}</tr>{/heading_row_end}

				{week_row_start}<tr class="week">{/week_row_start}
				{week_row_class_start}<td class="{week_class}">{/week_row_class_start}
				{week_day_cell}{week_day}{/week_day_cell}
				{week_row_class_end}</td>{/week_row_class_end}
				{week_row_end}</tr>{/week_row_end}

				{cal_row_start}<tr>{/cal_row_start}
				{cal_cell_start}<td>{/cal_cell_start}
				{cal_cell_start_today}<td>{/cal_cell_start_today}
				{cal_cell_start_other}<td class="other-month">{/cal_cell_start_other}

				{cal_cell_content}
					<div class="xday" data-date="'.$year.'-'.$month.'-{day}">
						{day}
						<div class="cont">{content}</div>
					</div>
				{/cal_cell_content}

				{cal_cell_content_today}
					<div class="xday highlight"  data-date="'.$year.'-'.$month.'-{day}">
						{day}
						<div class="cont">{content}</div>
					</div>
				{/cal_cell_content_today}

				{cal_cell_no_content}
				
					<div class="xday" data-date="'.$year.'-'.$month.'-{day}">{day}</div>			
				
				{/cal_cell_no_content}

				{cal_cell_no_content_today}
					<div class="xday highlight" data-date="'.$year.'-'.$month.'-{day}">{day}</div>
				{/cal_cell_no_content_today}

				{cal_cell_blank}&nbsp;{/cal_cell_blank}

				{cal_cell_other}{day}{cal_cel_other}

				{cal_cell_end}</td>{/cal_cell_end}
				{cal_cell_end_today}</td>{/cal_cell_end_today}
				{cal_cell_end_other}</td>{/cal_cell_end_other}
				{cal_row_end}</tr>{/cal_row_end}

				{table_close}</table>{/table_close}
		';

		$this->load->library('calendar', $prefs);

		$info = $this->main_model->get_calendar_list($year, $month);
		$contArray = array();
		foreach($info as $ndate){
			list($y,$m,$d) = explode("-",$ndate->WOEK_DATE);
			$contArray[$d] = $ndate->REMARK;
		}

		$data['calendar'] = $this->calendar->generate($this->uri->segment(3), $this->uri->segment(4),$contArray);
		
		
		return $this->load->view('intro',$data); 
	
	
	}
	public function ajax_set_p2_info()
	{
		$data['title'] = "생산계획등록";
		$data['setDate'] = $this->input->post("xdate");
		
		$data['INFO'] = $this->main_model->get_p2_info($this->input->post("xdate"));
		

		$this->load->view('ajax_p2_set',$data);
	}

	public function ajax_p2_insert()
	{
		$params['WOEK_DATE'] = $this->input->post("WOEK_DATE");
		$params['REMARK'] = $this->input->post("REMARK");
		$data = $this->main_model->ajax_p2_insert($params);
		echo json_encode($data);
	}
}
