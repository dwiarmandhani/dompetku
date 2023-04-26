<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
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
        $data['title'] = 'Menu Management';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('menu', 'Menu', 'required');
        $this->form_validation->set_rules('order', 'Order', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('templates/footer');
        } else {
            $query = "SELECT urutan FROM user_menu";
            $urutan = $this->db->query($query)->result_array();
            $is_found = false;

            foreach ($urutan as $item) {
                if ($item['urutan'] == $this->input->post('order')) {
                    $is_found = true;
                    break;
                }
            }

            if ($is_found === true) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">The order already exists.</div>');
                redirect('menu');
            } else {
                $this->db->insert('user_menu', ['menu' => $this->input->post('menu'), 'urutan' => $this->input->post('order')]);
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New menu added</div>');
                redirect('menu');
            }
        }
    }

    public function deletemenu($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user_menu');
        redirect('menu');
    }

    public function submenu()
    {
        $data['title'] = 'Sub Menu Management';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->load->model('Menu_model', 'menu');

        $data['subMenu'] = $this->menu->getSubMenu();

        $this->form_validation->set_rules('title', 'Name', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('url', 'URL', 'required');
        $this->form_validation->set_rules('icon', 'icon', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/submenu', $data);
            $this->load->view('templates/footer');
        } else {
            $data = [
                'title' => $this->input->post('title'),
                'menu_id' => $this->input->post('menu_id'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('is_active'),
            ];

            $this->db->insert('user_sub_menu', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Sub menu added</div>');

            redirect('menu/submenu');
        }
    }
    public function deletesubmenu($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user_sub_menu');
        redirect('menu/submenu');
    }

    public function openedit($id)
    {
        $data['menu'] = $this->db->get_where('user_menu', ['id' => $id])->row();
        echo json_encode($data);
    }
    public function editmenu()
    {
        $this->form_validation->set_rules('menu', 'Menu', 'required');
        $this->form_validation->set_rules('order', 'Order', 'required');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Menu edited failed!</div>');

            redirect('menu');
        } else {
            $id = $this->input->post('menu_id');
            $data = array(
                'menu' => $this->input->post('menu'),
                'urutan' => $this->input->post('order')
            );

            $query = "SELECT urutan FROM user_menu WHERE id != $id";
            $urutan = $this->db->query($query)->result_array();
            $is_found = false;

            foreach ($urutan as $item) {
                if ($item['urutan'] == $this->input->post('order')) {
                    $is_found = true;
                    break;
                }
            }
            if ($is_found === true) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">The order already exists.</div>');
                redirect('menu');
            } else {
                $this->db->where('id', $id);
                $this->db->update('user_menu', $data);
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Menu has been edited</div>');
                redirect('menu');
            }
        }
    }
    public function opensubmenuedit($id)
    {
        $menu['submenu'] = $this->db->get_where('user_sub_menu', ['id' => $id])->row();
        echo json_encode($menu);
    }
    public function editsubmenu()
    {
        $this->form_validation->set_rules('judul', 'Title', 'required');
        $this->form_validation->set_rules('menu_select_id', 'Menu', 'required');
        $this->form_validation->set_rules('sub_menu_url', 'URL', 'required');
        $this->form_validation->set_rules('sub_menu_icon', 'Icon', 'required');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Sub menu edited failed! All form is required.</div>');

            redirect('menu/submenu');
        } else {
            $id = $this->input->post('subMenuId');
            $data = array(
                'title' => $this->input->post('judul'),
                'menu_id' => $this->input->post('menu_select_id'),
                'url' => $this->input->post('sub_menu_url'),
                'icon' => $this->input->post('sub_menu_icon'),
                'is_active' => $this->input->post('sub_menu_active'),
            );
            $this->db->where('id', $id);
            $this->db->update('user_sub_menu', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Sub menu has been edited</div>');
            redirect('menu/submenu');
        }
    }
}
