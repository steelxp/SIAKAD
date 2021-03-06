<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Agama extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('login')) {
    			redirect('auth');
    		}
    		else if($this->session->userdata('level') != 'baak'){
    				redirect('auth/logout');
    		}
    		else {
          $this->load->model('Agama_model');
          $this->load->library('form_validation');
        }
    }

    public function index()
    {
        $agama = $this->Agama_model->get_all();
        $data = array(
            'agama_data' => $agama
        );
        $data['site_title'] = 'SIMALA';
    		$data['title_page'] = 'Master Agama';
        $data['assign_js'] = 'agama/js/index.js';
        load_view('agama/agama_list', $data);
    }

    public function read($id)
    {
        $row = $this->Agama_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_agama' => $row->id_agama,
		'agama' => $row->agama,
	    );
            load_view('agama/agama_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('agama'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('agama/create_action'),
          	'id_agama' => set_value('id_agama'),
          	'agama' => set_value('agama'),
          	);
        $data['site_title'] = 'SIMALA';
    		$data['title_page'] = 'Masukan Data Agama';
        $data['assign_js'] = 'agama/js/index.js';
        load_view('agama/agama_form', $data);
    }

    public function create_action()
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->create();
        }
        else {
            $data ['agama']= $this->input->post('agama',TRUE);
            $this->Agama_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('agama'));
        }
    }

    public function update($id)
    {
        $row = $this->Agama_model->get_by_id($id);
        if ($row) {
            $data['button'] = 'Update';
            $data['action'] = site_url('agama/update_action');
            $data['id_agama'] = set_value('id_agama', $row->id_agama);
            $data['agama'] = set_value('agama', $row->agama);
            $data['site_title'] = 'SIMALA';
            $data['title_page'] = 'Masukan Data Agama';
            $data['assign_js'] = 'agama/js/index.js';
            load_view('agama/agama_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('agama'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_agama', TRUE));
        } else {
            $data = array(
		'agama' => $this->input->post('agama',TRUE),
	    );

            $this->Agama_model->update($this->input->post('id_agama', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('agama'));
        }
    }

    public function delete($id)
    {
        $row = $this->Agama_model->get_by_id($id);

        if ($row) {
            $this->Agama_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('agama'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('agama'));
        }
    }

    public function _rules()
    {
    	$this->form_validation->set_rules('agama', 'agama', 'trim|required');
    	$this->form_validation->set_rules('id_agama', 'id_agama', 'trim');
    	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}
