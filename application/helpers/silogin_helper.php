<?php

function is_logged_in()
{
    $silogin = get_instance();
    if (!$silogin->session->userdata('email')) {
        redirect('auth');
    } else {
        $role_id = $silogin->session->userdata('role_id');
        $menu = $silogin->uri->segment(1);

        // query menu
        $queryMenu = $silogin->db->get_where('user_menu', ['menu' => $menu])->row_array();
        $menu_id = $queryMenu['id'];

        $userAccess = $silogin->db->get_where('user_access_menu', ['role_id' => $role_id, 'menu_id' => $menu_id]);

        if ($userAccess->num_rows() < 1) {
            redirect('auth/blocked');
        }
    }
}
