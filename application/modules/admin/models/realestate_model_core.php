<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Memento category_model_core model
 *
 * This class handles category_model_core management related functionality
 *
 * @package		Admin
 * @subpackage	category_model_core
 * @author		dbcinfotech
 * @link		http://dbcinfotech.net
 */
class Realestate_model_core extends CI_Model 
{
	var $category,$menu;

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->category = array();
	}

	function check_post_permission($id)
	{
		$post = $this->get_estate_by_id($id);
		if(is_admin()==FALSE && $post->created_by!=$this->session->userdata('user_id'))
		{
			return FALSE;
		}
		else
			return TRUE;
	}

	function get_estate_by_id($id)
	{
		$query = $this->db->get_where('posts',array('id'=>$id,'status !='=>0));
		if($query->num_rows()>0)
		{
			return $query->row();
		}
		else
		{
			die('Estate not found');
		}
	}

	function update_estate($data,$id)
	{
		$this->db->update('posts',$data,array('id'=>$id));
	}

	function update_estate_meta($data,$id,$key)
	{
		$this->db->update('post_meta',$data,array('post_id'=>$id,'key'=>$key));
	}

	function insert_estate($data)
	{
		$this->db->insert('posts',$data);
		return $this->db->insert_id();
	}

	function insert_estate_meta($data)
	{
		$this->db->insert('post_meta',$data);
		return $this->db->insert_id();
	}

	function insert_extra_station($data){
		$this->db->insert('extra_station', $data);
		return $this->db->insert_id();
	}

	function get_all_estates_admin($start,$limit,$order_by='id',$order_type='asc')
	{

		if($this->session->userdata('filter_purpose')!='')
		{
			$this->db->where('purpose',$this->session->userdata('filter_purpose'));
		}

		if($this->session->userdata('filter_type')!='')
		{
			$this->db->where('type',$this->session->userdata('filter_type'));
		}

		if($this->session->userdata('filter_condition')!='')
		{
			$this->db->where('estate_condition',$this->session->userdata('filter_condition'));
		}

		if($this->session->userdata('filter_status')!='')
		{
			$this->db->where('status',$this->session->userdata('filter_status'));
		}
		else
		{
			$where = "(status=1 or status=2)";
			$this->db->where($where);
		}

		if($this->session->userdata('filter_orderby')!='')
		{
			$order_by 	= ($this->session->userdata('filter_orderby')!='')?$this->session->userdata('filter_orderby'):'id';
			$order_type = ($this->session->userdata('filter_ordertype')!='')?$this->session->userdata('filter_ordertype'):'DESC';
			$this->db->order_by($order_by,$order_type);
		}
		else
		{
			$this->db->order_by('id','desc');
		}

		if($this->input->post('id_search')!='')
			$this->db->where('id',$this->input->post('id_search'));
		

		$query = $this->db->get('posts',$limit,$start);
		return $query;
	}

	function count_all_estates_admin()
	{
		if($this->session->userdata('filter_purpose')!='')
		{
			$this->db->where('purpose',$this->session->userdata('filter_purpose'));
		}

		if($this->session->userdata('filter_type')!='')
		{
			$this->db->where('type',$this->session->userdata('filter_type'));
		}

		if($this->session->userdata('filter_condition')!='')
		{
			$this->db->where('estate_condition',$this->session->userdata('filter_condition'));
		}

		if($this->session->userdata('filter_status')!='')
		{
			$this->db->where('status',$this->session->userdata('filter_status'));
		}

		if($this->session->userdata('filter_orderby')!='')
		{
			$order_by 	= ($this->session->userdata('filter_orderby')!='')?$this->session->userdata('filter_orderby'):'title';
			$order_type = ($this->session->userdata('filter_ordertype')!='')?$this->session->userdata('filter_ordertype'):'ASC';
			$this->db->order_by($order_by,$order_type);
		}

		if($this->input->post('id_search')!='')
			$this->db->where('id',$this->input->post('id_search'));

		$query = $this->db->get('posts');
		return $query->num_rows();
	}

	function get_all_estates_agent($start,$limit,$order_by='id',$order_type='asc')
	{
		if($this->session->userdata('filter_purpose')!='')
		{
			$this->db->where('purpose',$this->session->userdata('filter_purpose'));
		}

		if($this->session->userdata('filter_type')!='')
		{
			$this->db->where('type',$this->session->userdata('filter_type'));
		}

		if($this->session->userdata('filter_condition')!='')
		{
			$this->db->where('estate_condition',$this->session->userdata('filter_condition'));
		}

		if($this->session->userdata('filter_status')!='')
		{
			$this->db->where('status',$this->session->userdata('filter_status'));
		}

		if($this->session->userdata('filter_orderby')!='')
		{
			$order_by 	= ($this->session->userdata('filter_orderby')!='')?$this->session->userdata('filter_orderby'):'title';
			$order_type = ($this->session->userdata('filter_ordertype')!='')?$this->session->userdata('filter_ordertype'):'ASC';
			$this->db->order_by($order_by,$order_type);
		}

		if($this->input->post('id_search')!='')
			$this->db->where('id',$this->input->post('id_search'));

		$this->db->where('created_by',$this->session->userdata('user_id'));
		$query = $this->db->get_where('posts',array('status !='=>0),$limit,$start);
		return $query;
	}

	function count_all_estates_agent()
	{
		if($this->session->userdata('filter_purpose')!='')
		{
			$this->db->where('purpose',$this->session->userdata('filter_purpose'));
		}

		if($this->session->userdata('filter_type')!='')
		{
			$this->db->where('type',$this->session->userdata('filter_type'));
		}

		if($this->session->userdata('filter_condition')!='')
		{
			$this->db->where('estate_condition',$this->session->userdata('filter_condition'));
		}

		if($this->session->userdata('filter_status')!='')
		{
			$this->db->where('status',$this->session->userdata('filter_status'));
		}

		if($this->session->userdata('filter_orderby')!='')
		{
			$order_by 	= ($this->session->userdata('filter_orderby')!='')?$this->session->userdata('filter_orderby'):'title';
			$order_type = ($this->session->userdata('filter_ordertype')!='')?$this->session->userdata('filter_ordertype'):'ASC';
			$this->db->order_by($order_by,$order_type);
		}

		if($this->input->post('id_search')!='')
			$this->db->where('id',$this->input->post('id_search'));
		
		$this->db->where('created_by',$this->session->userdata('user_id'));
		$query = $this->db->get_where('posts',array('status !='=>0));
		return $query->num_rows();
	}

	function get_all_estates($start,$limit,$order_by='id',$order_type='asc')
	{
		$this->db->order_by($order_by,$order_type);
		$query = $this->db->get_where('posts',array('status !='=>0),$limit,$start);
		return $query;
	}

	function count_all_estates()
	{
		$query = $this->db->get_where('posts',array('status !='=>0));
		return $query->num_rows();
	}

	function get_location_id_by_name($name,$type,$parent)
	{
		$query = $this->db->get_where('locations',array('status'=>1,'name'=>$name,'type'=>$type,'parent'=>$parent));
		//echo $this->db->last_query();
		if($query->num_rows()>0)
		{
			$row = $query->row();
			//echo'<br>';echo $row->id;die();
			return $row->id;
		}
		else
		{
			$data = array();
			$data['type'] 	= $type;
			$data['name'] 	= $name;
			$data['parent']	= $parent;
			$this->db->insert('locations',$data);
			return $this->db->insert_id();
		}
	}

	function get_locations_json($term='',$type,$parent)
	{
		$this->db->like('name',$term);
		$query = $this->db->get_where('locations',array('status'=>1,'type'=>$type,'parent'=>$parent));
		$data = array();
		foreach ($query->result() as $row) {
			$val = array();
			$val['id'] = $row->id;
			$val['label'] = $row->name;
			$val['value'] = trim($row->name);
			array_push($data,$val);
		}
		return $data;
	}

	function get_all_locations_json($term='')
	{
		$this->db->like('name',$term);
		$query = $this->db->get_where('locations',array('status'=>1));
		$data = array();
		foreach ($query->result() as $row) {
			$val = array();
			$val['id'] = $row->id;
			$val['label'] = $row->name;
			$val['value'] = trim($row->name);
			array_push($data,$val);
		}
		return $data;
	}

	function get_all_locations_by_range($start,$limit='',$sort_by='')
	{
		$data = array();
		$this->db->order_by($sort_by, "asc");
		$this->db->where('status',1);
		$this->db->where('parent',0);
		$query = $this->db->get('locations');
		foreach ($query->result() as $country) {
			array_push($data,$country);
			$state_query = $this->db->get_where('locations',array('status'=>1,'parent'=>$country->id));
			foreach ($state_query->result() as $state) {
				array_push($data,$state);
				$city_query = $this->db->get_where('locations',array('status'=>1,'parent'=>$state->id));
				foreach ($city_query->result() as $city) {
					array_push($data,$city);
				}
			}
		}

		return array_slice($data,$start,$limit,true);
	}
	
	function count_all_locations()
	{
		$this->db->where('status',1); 
		$query = $this->db->get('locations');
		return $query->num_rows();
	}
	
	function insert_location($data)
	{
		$this->db->insert('locations',$data);
		return $this->db->insert_id();
	}

	function get_locations_by_type($type)
	{
		$query = $this->db->get_where('locations',array('type'=>$type,'status'=>1));
		return $query;
	}

	function get_location_by_id($id)
	{
		$query = $this->db->get_where('locations',array('id'=>$id));
		if($query->num_rows()<=0)
		{
			echo 'Invalid page id';die;
		}
		else
		{
			return $query->row();
		}
	}

	function delete_location_by_id($id)
	{
		$data['status'] = 0;
		$this->db->update('locations',$data,array('id'=>$id));
		$this->db->update('locations',$data,array('parent'=>$id));
	}


	function update_location($data,$id)
	{
		$this->db->update('locations',$data,array('id'=>$id));
	}

	#trainlines

	function get_all_trainlines_by_range($start,$limit='',$sort_by='')
	{
		$data = array();
		$this->db->order_by($sort_by, "asc");
		$this->db->where('status',1);
		$this->db->where('parent',0);
		$query = $this->db->get('trainlines');
		foreach ($query->result() as $country) {
			array_push($data,$country);
			$state_query = $this->db->get_where('trainlines',array('status'=>1,'parent'=>$country->id));
			foreach ($state_query->result() as $state) {
				array_push($data,$state);
				$city_query = $this->db->get_where('trainlines',array('status'=>1,'parent'=>$state->id));
				foreach ($city_query->result() as $city) {
					array_push($data,$city);
				}
			}
		}

		return array_slice($data,$start,$limit,true);
	}

	function count_all_trainlines()
	{
		$this->db->where('status',1); 
		$query = $this->db->get('trainlines');
		return $query->num_rows();
	}

	function get_trainlines_by_type($type)
	{
		$query = $this->db->get_where('trainlines',array('type'=>$type,'status'=>1));
		return $query;
	}

	function insert_trainline($data)
	{
		$this->db->insert('trainlines',$data);
		return $this->db->insert_id();
	}

	function get_trainline_by_id($id)
	{
		$query = $this->db->get_where('trainlines',array('id'=>$id));
		if($query->num_rows()<=0)
		{
			echo 'Invalid page id';die;
		}
		else
		{
			return $query->row();
		}
	}

	function update_trainline($data,$id)
	{
		$this->db->update('trainlines',$data,array('id'=>$id));
	}

	function delete_trainline_by_id($id)
	{
		$data['status'] = 0;
		$this->db->update('trainlines',$data,array('id'=>$id));
		$this->db->update('trainlines',$data,array('parent'=>$id));
	}

	function get_trainlines_json($term='',$type,$parent)
	{
		$this->db->like('name',$term);
		$query = $this->db->get_where('trainlines',array('status'=>1,'type'=>$type,'parent'=>$parent));
		$data = array();
		foreach ($query->result() as $row) {
			$val = array();
			$val['id'] = $row->id;
			$val['label'] = $row->name;
			$val['value'] = trim($row->name);
			array_push($data,$val);
		}
		return $data;
	}

	function get_trainline_id_by_name($name,$type,$parent)
	{
		//$query = $this->db->get_where('trainlines',array('status'=>1,'name'=>$name,'type'=>$type,'parent'=>$parent));
		$this->db->from('trainlines');
		$this->db->where_in('id', $name);
		$this->db->where('status', 1);
		$this->db->where('type', $type);
		$this->db->where('parent', $parent);
		$query = $this->db->get();

		if($query->num_rows()>0)
		{
			$row = $query->row();
			return $row->id;
		}
		else
		{

			$data = array();
			$data['type'] 	= $type;
			$data['name'] 	= $name;
			$data['parent']	= $parent;

			$this->db->insert('trainlines',$data);
			return $this->db->insert_id();
		}
	}
	#trainlines
	

	function get_all_payment_history($start,$limit,$order_by='id',$order_type='asc')
	{
		if($this->input->post('transaction_id')!='')
		{
			$this->db->where('unique_id',$this->input->post('transaction_id'));
		}

		$this->db->order_by($order_by,$order_type);
		$query = $this->db->get_where('user_package',array('status !='=>0),$limit,$start);
		return $query;
	}

	function count_all_payment_history()
	{
		if($this->input->post('transaction_id')!='')
		{
			$this->db->where('unique_id',$this->input->post('transaction_id'));
		}
		
		$query = $this->db->get_where('user_package',array('status !='=>0));
		return $query->num_rows();
	}

	function deletehistory($id=0)
	{
		$this->db->update('user_package',array('status'=>0),array('id'=>$id));
	}

	function delete_post_by_id($id='')
	{
		$this->db->delete('posts', array('id' => $id));
		// delete table fields from table extra station by post id..
		$this->db->delete('extra_station', array('post_id' => $id));
        //$this->db->update('posts',$data,array('id'=>$id));
	}

	function update_post_by_id($data,$id)
	{
		$this->db->update('posts',$data,array('id'=>$id));
	}	

	function get_all_emails_admin($start,$limit)
	{
		if($start=='all')
		{
			$this->db->like('key','query_email');
			$query = $this->db->get_where('user_meta',array('status'=>1));
			return $query;	
		}
		else
		{
			$this->db->like('key','query_email');
			$query = $this->db->get_where('user_meta',array('status'=>1),$limit,$start);
			return $query;			
		}
	}

	function count_all_emails_admin()
	{
		$this->db->like('key','query_email');
		$query = $this->db->get_where('user_meta',array('status'=>1));
		return $query->num_rows();
	}

	function get_all_emails_agent($start,$limit)
	{
		if($start=='all')
		{
			$this->db->like('key','query_email');
			$query = $this->db->get_where('user_meta',array('status'=>1,'user_id'=>$this->session->userdata('user_id')));
			return $query;
		}
		else
		{
			$this->db->like('key','query_email');
			$query = $this->db->get_where('user_meta',array('status'=>1,'user_id'=>$this->session->userdata('user_id')),$limit,$start);
			return $query;			
		}
	}

	function count_all_emails_agent()
	{
		$this->db->like('key','query_email');
		$query = $this->db->get_where('user_meta',array('status'=>1,'user_id'=>$this->session->userdata('user_id')));
		return $query->num_rows();
	}

	function get_all_emails()
	{
		if(!is_admin())
			$this->db->where('user_id',$this->session->userdata('user_id'));

		$this->db->like('key','query_email');
		$query = $this->db->get_where('user_meta',array('status'=>1));
		return $query;
	}

	function get_feature_payment_data_by_unique_id($unique_id)
	{
		$this->db->where('key','featurepayment_'.$unique_id);
		$query = $this->db->get_where('post_meta',array('status'=>1));
		return $query;
	}


	function get_all_estates_by_user_type($return_type='all')
	{

		if(!is_admin())
		$this->db->where('created_by',$this->session->userdata('user_id'));
		
		if($return_type=='all')
		{
			$query = $this->db->get('posts');
			return $query;			
		}
		else
		{
			$query = $this->db->get('posts');
			return $query->num_rows();						
		}
	}

	//add new line
	function insertIntoExtraStationTable($id , $selectedStation , $selectedTrainLineIds){
		// var_dump($id, $selectedStation, $selectedTrainLineIds);
	    $stationTempArray = array();
	    $stationTempArray['post_id'] = $id;
	    
	    foreach($selectedTrainLineIds as $key => $trainlineid)
	    {
		    foreach($selectedStation as $key => $stationId)
		    {
		    	if(!empty($stationId))
				{
					$validate_trainline_station = $this->validate_trainline_station($trainlineid, $stationId);
		    		if($validate_trainline_station){
		    			$stationTempArray['station_id'] = $stationId;
			    		$stationTempArray['trainline_id']   = $trainlineid;

			    		$lastInsertedPostId = $this->insert_extra_station($stationTempArray);
		    		}
		    	}
		    }   	
	    		
        }
        return true;
	}
	//get extra station name by post id from trainline table
	function get_extra_estate_by_id($id)
	{
		$query = $this->db->get_where('extra_station',array('post_id'=>$id));
		$stations = $query->result();
		if($query->num_rows()>0)
		{
			$stationArray = array();
			foreach($stations as $station) 
			{
				 $stationArray[]= $station->station_id;
			}
			
			//$stationNameArray = array();
			foreach ($stationArray as $station) {
				$this->db->from('trainlines');
				$this->db->where_in('id', $station);
				$query  =  $this->db->get();
				$result = $query->row();
				$stationNameArray[] = array($result->name ,$result->id);	
			}
			//echo'<pre>';print_r($stationNameArray);die();
			return $stationNameArray;
		}
		else
		{
			//die('Estate not found');
		}
	}
	function delete_staions_table($id)
	{

		$this->db->delete('extra_station', array('post_id' => $id));
	}

	function validate_trainline_station($trainline, $station){
		$this->db->where('id', $station);
		$this->db->where('parent', $trainline);
		$this->db->where('type', 'station');
		$this->db->from('ezc_trainlines');
		$count = $this->db->count_all_results();
		return ($count == 1)?TRUE:FALSE;
	}

	function get_trainline_stations($id){
		$trainline_stations = array();
		$this->db->select('trainline_id, GROUP_CONCAT(station_id) AS station_ids');
		$this->db->from('ezc_extra_station AS es');
		$this->db->where('post_id', $id);
		$this->db->group_by('trainline_id');
		$stations = $this->db->get()->result();

		if($stations){
			foreach($stations as $station){
				$trainline_stations['trainlines'][] = $station->trainline_id;
				if($station->station_ids != ''){
					$available_stations = explode(",", $station->station_ids);
					foreach($available_stations as $key => $val){
						$station_info = $this->db->select('name')->from('ezc_trainlines')->where('id', $val)->get()->row();
						$trainline_stations[$station->trainline_id]['station_id'][] = $val;
						$trainline_stations[$station->trainline_id]['station_name'][] = $station_info->name;
					}
				}
			}
		}
		// var_dump($trainline_stations);
		return $trainline_stations;
	}

}

/* End of file category_model_core.php */
/* Location: ./system/application/models/category_model_core.php */