<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Financial extends CI_Controller
{
    /* membuat setting access */
    public function __construct()
    {
        parent::__construct();
        // cek login atau belum
        is_logged_in();
    }
    public function index()
    {
        $data['title'] = 'Incomes Resources';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('financial/index', $data);
        $this->load->view('templates/footer');
    }
    // income list
    public function incomelist()
    {
        $data['title'] = 'Income List';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $id = $data['user']['id'];
        $data['incomelist'] = $this->db->get_where('user_income_list', ['user_id' => $id])->result_array();

        $this->form_validation->set_rules('incomeList', 'Income Name', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('financial/incomelist', $data);
            $this->load->view('templates/footer');
        } else {
            $this->db->insert('user_income_list', ['user_id' => $id, 'income_name' => $this->input->post('incomeList')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Income List added</div>');
            redirect('financial/incomelist');
        }
    }

    public function editincomelist()
    {
        $this->form_validation->set_rules('incomelist_id', 'Income ID', 'required');
        $this->form_validation->set_rules('incomename', 'Income Name', 'required');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Income List edited failed!</div>');

            redirect('financial/incomelist');
        } else {
            $id = $this->input->post('incomelist_id');
            $data = array(
                'income_name' => $this->input->post('incomename')
            );

            $this->db->where('id', $id);
            $this->db->update('user_income_list', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Income List has been edited</div>');
            redirect('financial/incomelist');
        }
    }
    public function openincomelistedit($id)
    {
        $incomelist['incomelist'] = $this->db->get_where('user_income_list', ['id' => $id])->row();
        echo json_encode($incomelist);
    }
    public function deleteincomelist($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user_income_list');
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Income List item has been deleted</div>');

        redirect('financial/incomelist');
    }
    // category income list
    public function categorylist()
    {
        $data['title'] = 'Category List';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $id = $data['user']['id'];
        $data['categorylist'] = $this->db->get_where('user_category_list', ['user_id' => $id])->result_array();

        $this->form_validation->set_rules('categoryList', 'Category Name', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('financial/categorylist', $data);
            $this->load->view('templates/footer');
        } else {
            $this->db->insert('user_category_list', ['user_id' => $id, 'category_name' => $this->input->post('categoryList')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Category List added</div>');
            redirect('financial/categorylist');
        }
    }
    public function editcategorylist()
    {
        $this->form_validation->set_rules('categorylist_id', 'Category ID', 'required');
        $this->form_validation->set_rules('categoryListName', 'Category Name', 'required');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Category List edited failed!</div>');

            redirect('financial/categorylist');
        } else {
            $id = $this->input->post('categorylist_id');
            $data = array(
                'category_name' => $this->input->post('categoryListName')
            );

            $this->db->where('id', $id);
            $this->db->update('user_category_list', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Category List has been edited</div>');
            redirect('financial/categorylist');
        }
    }
    public function opencategorylistedit($id)
    {
        $categorylist['categoryList'] = $this->db->get_where('user_category_list', ['id' => $id])->row();
        echo json_encode($categorylist);
    }
    public function deletecategorylist($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user_category_list');
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Category List item has been deleted</div>');
        redirect('financial/categorylist');
    }
    // My Wallet
    // Rules
    // Jumlah harus diisi lewat cash in/ cashout
    // kasih keteranga di bawah field
    public function mywallet()
    {
        $data['title'] = 'My Wallet';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $id = $data['user']['id'];
        $data['myWallet'] = $this->db->get_where('user_wallet', ['user_id' => $id])->result_array();

        $this->form_validation->set_rules('walletName', 'Wallet Name', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('financial/mywallet', $data);
            $this->load->view('templates/footer');
        } else {
            $this->db->insert('user_wallet', ['user_id' => $id, 'wallet_name' => $this->input->post('walletName'), 'total_balance' => 0]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Wallet added</div>');
            redirect('financial/mywallet');
        }
    }

    public function openwalletedit($id)
    {
        $wallet['wallet'] = $this->db->get_where('user_wallet', ['id' => $id])->row();
        echo json_encode($wallet);
    }
    public function editwallet()
    {
        $this->form_validation->set_rules('wallet_id', 'Wallet ID', 'required');
        $this->form_validation->set_rules('wallet_name', 'wallet Name', 'required');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wallet edited failed!</div>');

            redirect('financial/mywallet');
        } else {
            $id = $this->input->post('wallet_id');
            $data = array(
                'wallet_name' => $this->input->post('wallet_name')
            );

            $this->db->where('id', $id);
            $this->db->update('user_wallet', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Wallet has been edited</div>');
            redirect('financial/mywallet');
        }
    }
    public function detelewallet($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user_wallet');
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Wallet item has been deleted</div>');

        redirect('financial/mywallet');
    }
}
