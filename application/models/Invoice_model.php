<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends CI_Model {

	public function count_results()
	{
		return $this->db->count_all_results('invoice');
	}


	public function return_items($config)
	{
		$this->db->limit($config['limit']);
        $this->db->offset($config['offset']);
        $this->db->order_by('created_on', 'DESC');
		return $this->db->get('invoice')->result();
	}


	public function return_invoice_items($invoice_id)
	{
		$this->db->where('invoice_id', $invoice_id);
		return $this->db->get('invoice_items')->result();
	}

}

/* End of file Invoice_model.php */
/* Location: ./application/models/Invoice_model.php */