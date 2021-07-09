<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends CI_Controller {

	public function __construct()
        {
                parent::__construct();
                $this->load->library('session');
        }

	public function index()
	{
		$this->load->view('invoice_page');
	}

	public function generate_invoice()
	{
		if (!$this->input->is_ajax_request() || $this->input->method() !== "post") {
			show_404();
		}
         
        $result['csrf_token'] = $this->security->get_csrf_hash();
     
        $this->load->library('form_validation');
        $config = array(
		        array(
		                'field' => 'customer_name',
		                'label' => 'Customer Name',
		                'rules' => 'required|max_length[100]'
		        ),
		        array(
		                'field' => 'contact_number',
		                'label' => 'Contact Number',
		                'rules' => 'required|max_length[15]'
		        ),
		        array(
		                'field' => 'billing_address',
		                'label' => 'Billing Address',
		                'rules' => 'required'
		        ),
		        array(
		                'field' => 'invoice_items',
		                'label' => 'Invoice Items',
		                'rules' => 'required|callback_check_invoice_items'
		        ),
		        array(
		                'field' => 'discount',
		                'label' => 'Discount',
		                'rules' => 'required|numeric'
		        ),
		        array(
		                'field' => 'discount_type',
		                'label' => 'Discount Type',
		                'rules' => 'in_list[ ,%]'
		        )
		);
		$this->form_validation->set_rules($config);
        if ($this->form_validation->run() == FALSE) {
        	$result['error'] = validation_errors();
        }
        else{
        	$form_data = $this->input->post();

        	// validate and recreate invoice items 
            $invoice_items = json_decode($form_data['invoice_items']);
            $invoice_items_data = [];

            foreach ($invoice_items as $value) {
            	$required = array('item_name', 'quantity', 'unit_price', 'tax', 'description');
            	if (count(array_diff_key(array_flip($required), (array) $value)) > 0 || count((array) $value) !== count($required)) {
            		$result['error'] = "Invoice Items field contains invalid data.";
            		die(json_encode($result));
            	}

            	$new_data = [
                    'item_name'     => $value->item_name,
                    'quantity'      => $value->quantity,
                    'unit_price'    => $value->unit_price,
                    'tax'          => $value->tax,
                    'description'   => $value->description,
            	];


            	array_push($invoice_items_data, $new_data);
            	
            }
            
            
        	// insert invoice data
        	$invoice_data        = [
               'customer_name'     => $form_data['customer_name'],
               'contact_number'    => $form_data['contact_number'],
               'billing_address'   => $form_data['billing_address'],
               'discount'          => $form_data['discount'],
               'discount_type'     => $form_data['discount_type'],
        	];
        	$insert_invoice_data = $this->db->insert('invoice', $invoice_data);

        	if ($this->db->affected_rows() != 1) {
        		$result['error'] = "Something went wrong, please refresh and try again.";
        	}
        	else{
        		$invoice_id = $this->db->insert_id();

        		// insert invoice id to items array
        		foreach ($invoice_items_data as $key => $value) {
        			$invoice_items_data[$key]['invoice_id'] = $invoice_id;
        		}

        		// insert invoice items
        		$insert_invoice_items = $this->db->insert_batch('invoice_items', $invoice_items_data);
        		if ($this->db->affected_rows() != count($invoice_items_data)) {
        			$this->db->where('id', $invoice_id);
        			$this->db->delete('invoice');

        			$this->db->where('invoice_id', $invoice_id);
        			$this->db->delete('invoice_items');
        			$result['error'] = "Something went wrong, please refresh and try again.";
        		}
        		else{
        			$result['success'] = 'Invoice generated successfully, please <a href="'.base_url('invoice/info/'.$invoice_id).'" title="Check invoice Info">click here</a> to check newly generated invoice';
        		}
        		
        	}
        }
        die(json_encode($result));
	}


	public function list()
	{
		$this->load->model('invoice_model');
		$num_of_results = $this->invoice_model->count_results();

		if ($num_of_results > 0) {
            $config['base_url'] = base_url('invoice/list');
            $config['total_rows'] = $num_of_results;
            $config['per_page'] = 15;
            $config['uri_segment'] = 3;
            $config['use_page_numbers'] = TRUE;
            $config['num_links'] = 1;
            $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
            $config['full_tag_close'] = '</ul>';
            $config['first_link'] = 'First';
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_tag_close'] = '</li class="page-item">';
            $config['last_link'] = 'Last';
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_tag_close'] = '</li class="page-item">';
            $config['next_link'] = '&gt;';
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tag_close'] = '</li class="page-item">';
            $config['prev_link'] = '&lt;';
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tag_close'] = '</li class="page-item">';
            $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li >'; 
            $config['attributes'] = array('class' => 'page-link');
            $this->load->library('pagination');
            $this->pagination->initialize($config);

            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
            $config_result = [
              'limit'  => $config['per_page'],
              'offset' => ($page - 1) * $config['per_page']
            ];

            $result['count']         =  ($config_result['offset'] + 1);
            $result['invoice_items'] = $this->invoice_model->return_items($config_result);
		 	$result['pagination']    = $this->pagination->create_links();
		}
		else{
		 	$result['message'] = 'No results found';
		}

		$this->load->view('invoice_list_page', $result);

	}

	public function info()
	{
		$this->load->model('invoice_model');
		$item = $this->uri->segment(3);
		$invoice = $this->db->get_where('invoice', ['id' => $item]);
		if ($invoice->num_rows() < 1) {
			show_404();
		}

		$result['invoice'] = $invoice->row();
		$result['invoice_items'] = $this->invoice_model->return_invoice_items($item);
		
		$this->load->view('invoice_info_page', $result);
	}


	public function delete()
	{
		if ($this->input->method() !== "post") {
			show_404();
		}
		$item = $this->uri->segment(3);
		$invoice = $this->db->get_where('invoice', ['id' => $item]);
		if ($invoice->num_rows() < 1) {
			show_404();
		}

		// first delte invoice
		$this->db->where('id', $item);
		$delete_invoice = $this->db->delete('invoice');
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('messsage', 'The selected item was successfully deleted.');
            $this->session->set_flashdata('messsage_class', 'alert-success');
		}
		else{
            $this->session->set_flashdata('messsage', 'Something went wrong, please try again later.');
            $this->session->set_flashdata('messsage_class', 'alert-danger');
		}
        
		redirect($_SERVER['HTTP_REFERER'],'refresh');
	}



	public function generate_pdf()
	{
		$this->load->library('pdf');
		$this->load->model('invoice_model');
		$item = $this->uri->segment(3);
		$invoice = $this->db->get_where('invoice', ['id' => $item]);
		if ($invoice->num_rows() < 1) {
			show_404();
		}
         
        $result['invoice'] = $invoice->row();
		$result['invoice_items'] = $this->invoice_model->return_invoice_items($item);

		$content = $this->load->view('components/pdf_view', $result, TRUE);
		$this->pdf->loadHtml($content);
		$this->pdf->setPaper('A4', 'portrait');
		$this->pdf->render();
		$this->pdf->stream("Invoice-E-".$item.".pdf", array("Attachment" => 0));

	}


	// form validation methods
	public function check_invoice_items($str)
    {
            if (json_decode($str) === null || count(json_decode($str)) < 1)
            {
                    $this->form_validation->set_message('check_invoice_items', 'The {field} field contains invalid data');
                    return FALSE;
            }
            else
            {
                    return TRUE;
            }
    }
}
