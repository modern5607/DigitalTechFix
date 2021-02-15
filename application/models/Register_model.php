<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}



	/* member 정보호출 */
	public function get_userchk($obj,$val)
	{
		$this->db->where($obj,$val);
		$res = $this->db->get("T_MEMBER");

		return $res->row();

	}


	public function member_formupdate($params,$idx)
	{
		if(!empty($idx)){
			$this->db->update("T_MEMBER",$params,array("IDX"=>$idx));
		}else{
			$this->db->insert("T_MEMBER",$params);
			$idx = $this->db->insert_id();
		}
		return $idx;
	}

	public function member_formUpdate_info($params,$idx)
	{
		$this->db->update("T_MEMBER",$params,array("IDX"=>$idx));
		return $this->db->affected_rows();
	}



	public function ajax_chk_memberid($id)
	{
		$this->db->select("COUNT(*) as cnt");
		$this->db->where("ID",$id);
		$query = $this->db->get("T_MEMBER");
		$data['msg'] = "사용가능한 아이디입니다.";
		$data['state'] = 1;
		if($query->row()->cnt > 0){
			$data['msg'] = "사용중인 아이디입니다.";
			$data['state'] = 2;
		}
		return $data;
	}


	public function get_member_info($idx)
	{

		$data = $this->db->where(array('IDX'=>$idx))
						->get("T_MEMBER");
		return $data->row();
	}


	public function get_member_list($param,$start=0,$limit=20)
	{
		if(!empty($param['ID']) && $param['ID'] != ""){
			$this->db->like("ID",$param['ID']);
		}

		if(!empty($param['NAME']) && $param['NAME'] != ""){
			$this->db->like("NAME",$param['NAME']);
		}

		if(!empty($param['LEVEL']) && $param['LEVEL'] != ""){
			$this->db->where("LEVEL",$param['LEVEL']);
		}
		$this->db->limit($limit,$start);
		$res = $this->db->get("T_MEMBER");
		return $res->result();
	}


	public function get_member_cut($param)
	{
		$this->db->select("COUNT(*) as CUT");
		if(!empty($param['ID']) && $param['ID'] != ""){
			$this->db->where("ID",$param['ID']);
		}

		if(!empty($param['NAME']) && $param['NAME'] != ""){
			$this->db->where("NAME",$param['NAME']);
		}

		if(!empty($param['LEVEL']) && $param['LEVEL'] != ""){
			$this->db->where("LEVEL",$param['LEVEL']);
		}
		$res = $this->db->get("T_MEMBER");
		return $res->row()->CUT;
	}


	public function ajax_savelevel_update($param)
	{
		
		$this->db->set("LEVEL",$param['level']);
		$this->db->where("IDX",$param['idx']);
		$this->db->update("T_MEMBER");

		return $this->db->affected_rows();

	}


	public function get_ver_list($start,$limit)
	{
		$this->db->limit($limit,$start);
		$query = $this->db->get("T_VER");



		return $query->result();

	}


	public function get_ver_cut()
	{
		$this->db->select("COUNT(*) as CUT");
		$query = $this->db->get("T_VER");



		return $query->row()->CUT;

	}


	public function upload_ver_form($param,$MIDX = "")
	{
		if($MIDX == ""){
			$query = $this->db->insert("T_VER",$param);
			$xxx = $this->db->insert_id();
		}else{
			$this->db->where("IDX",$MIDX);
			$query = $this->db->update("T_VER",$param);
			$xxx = $MIDX;
		}
		return  $xxx;
	}


	public function delete_ver_form($param)
	{
		$this->db->where("IDX",$param['IDX']);
		$this->db->delete("T_VER");
		return $this->db->affected_rows();
	}


	public function modified_ver_form($param)
	{
		$this->db->where("IDX",$param['IDX']);
		$query = $this->db->get("T_VER");
		return $query->row();

	}

	public function get_log_list($data)
	{		
		$this->db->insert("T_DT_LOG",$data);
		return $this->db->insert_id();
	}

	public function get_log_update($user_id,$ip,$state) 
	{
		date_default_timezone_set('Asia/Seoul');
		$this->db->set('logout_time',date("Y-m-d H:i:s"));
		$this->db->set('state', $state);
		$this->db->where('user_id', $user_id);
		$this->db->where('ip', $ip);
		$this->db->order_by("login_time","DESC");
        $this->db->limit(1);
		$this->db->update("T_DT_LOG");
	}


	public function get_userlog_list($param,$start=0,$limit=20)
	{
		if(!empty($param['LOGIN']) && $param['LOGIN'] != ""){
			$this->db->where("login_time BETWEEN '{$param['LOGIN']} 00:00:00' AND '{$param['LOGIN']} 23:59:59'");
		}
		
		if(empty($param['ADMIN'])){
			$this->db->where("user_id !='admin'");
		}
		

		$this->db->order_by("login_time","desc");
		$this->db->limit($limit,$start);
		$res = $this->db->get("T_DT_LOG");
		return $res->result();
	}

	public function get_userlog_cut($param)
	{
		if(!empty($param['LOGIN']) && $param['LOGIN'] != ""){
			$this->db->where("login_time BETWEEN '{$param['LOGIN']} 00:00:00' AND '{$param['LOGIN']} 23:59:59'");
		}

		if(empty($param['LOGIN'])){
			$this->db->where("user_id !='admin'");
		}

		$this->db->select("COUNT(*) as CUT");

		$res = $this->db->get("T_DT_LOG");
		return $res->row()->CUT;
	}

	


}