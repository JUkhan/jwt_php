<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sp_model extends CI_Model {

	public function call_sp($spname, $params)
	{
		try{
		
			$paramStr='';
			if($params){
			      foreach ($params as $key => $val)
			      {
			        $params[$key] = '"' .  $this->db->escape_str($val) . '"';
			      }
			     $paramStr= implode(', ', $params);
		     }
			   $query=  'CALL ' . $spname . '(' . $paramStr . ');' ;
			   $qry_res    = $this->db->query($query);

		       $res        = $qry_res->result();

		       $qry_res->next_result(); // Dump the extra resultset.
		       $qry_res->free_result(); // Does what it says.

	       return $res;
	   }catch(Exception $ex){
	   	   throw $ex;
	   }
		//return $this->db->call_procedure($spname, $params);
	}
	
	public function get_all($tableName, $key, $direction){
		$this->db->select('*');
		$this->db->from($tableName);
		if(isset($key)){
			$this->db->order_by($key, $direction);	
		}
		$query = $this->db->get();
		if ($query->num_rows() >= 1)
		{
			return $query->result();
		}
		return array();
	}
	public function where($tableName, $where, $key, $direction){
		$this->db->select('*');
		$this->db->from($tableName);
		 $this->db->where($where, NULL, FALSE);  
		if(isset($key)){
			$this->db->order_by($key, $direction);	
		}
		$query = $this->db->get();
		if ($query->num_rows() >= 1)
		{
			return $query->result();
		}
		return array();
	}
	public function create($tableName, $params)
	{
		foreach ($params as $key => $val)
      	{
      		if($key !='tableName'){
        		$this->db->set($key,  $val);
        	}
        }
      			
		$this->db->insert($tableName);
		return $this->db->insert_id();
	}

	public function update($tableName, $id, $data, $pk){
		 return $this->db->where($pk, $id)->update($tableName, $data);
	}
	public function find($tableName, $id, $pk)
	{
		$user = new stdClass();

		$this->db->select('*');
		$this->db->from($tableName);
		$this->db->where($pk, $id);
		$this->db->limit(1);

		$query = $this->db->get();
		
		return $query->result();			
		
	}
	public function remove($tableName, $id, $pk)
	{
		$this->db->where($pk, $id);
		$this->db->delete($tableName);
		$this->db->limit(1);
	}
	 public function call_sp_out($spname, $params, $out_params){
	 	$paramStr='';
	 	$out_params_str='';
	 	$select_out_params_sql='';

		if($params){
		      foreach ($params as $key => $val)
		      {
		        $params[$key] = '"' .  $this->db->escape_str($val) . '"';
		      }
		     $paramStr= implode(', ', $params);
	    }

	     if ($out_params)
		 {
		 	  foreach ($out_params as $key => $val)
		      {
		         $out_params[$key] = '@' . $val;
		      }
			  $out_params_str = implode(', ', $out_params);
			  $select_out_params_sql = 'SELECT ' . $out_params_str . ';';
			  
		 }
		 if($params){

		 	  // We need a comma to separate regular parameters from out parameters in the CALL statement
			  $out_params_str = ',' . $out_params_str;
		 }
	    $query=  'CALL ' . $spname . '(' . $paramStr . $out_params_str. ');' . $select_out_params_sql;
	    $qry_res    = $this->db->query($query);

        $res[]        = $qry_res->result();

        $res[] =$qry_res->next_result(); // Dump the extra resultset.
        $qry_res->free_result(); // Does what it says.
        return $res;
	}
    
}

/* End of file users.php */
/* Location: ./application/models/users.php */