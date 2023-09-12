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

    // move money
    public function openmovemoney($id)
    {
        $email = $this->session->userdata('email');
        $query = $this->db->select('id')
            ->from('user')
            ->where('email', $email)
            ->get();
        $userid = $query->row();
        $money['money'] = $this->db->get_where('user_wallet', ['id' => $id])->row();
        $money['walletdestitanion'] = $this->db->get_where('user_wallet', ['user_id' => $userid->id])->result_array();
        echo json_encode($money);
    }

    public function movemoney()
    {
        $this->form_validation->set_rules('select_destination', 'Destination', 'required');
        $this->form_validation->set_rules('money_amount', 'Your Amount', 'required');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed transaction!</div>');

            redirect('financial/mywallet');
        } else {
            $walletid = $this->input->post('money_id');
            $walletBalance = $this->input->post('money_wallet_hidden');
            $amount = $this->input->post('money_amount');
            $destionationSelected = $this->input->post('select_destination');
            $walletOption = $this->input->post('wallet_option');

            if ($walletOption !== $destionationSelected) {
                if ((int)$amount <= (int)$walletBalance) {
                    // start transaction
                    $this->db->trans_begin();

                    $email = $this->session->userdata('email');
                    $queryUser = $this->db->select('id')
                        ->from('user')
                        ->where('email', $email)
                        ->get();
                    $resultUser = $queryUser->row();
                    $userid = $resultUser->id;
                    $queryDestionation = $this->db->select('total_balance')
                        ->from('user_wallet')
                        ->where('user_id', $userid)
                        ->where('wallet_name', $destionationSelected)
                        ->get();
                    $resultBalance = $queryDestionation->row();
                    $destionationBalance = $resultBalance->total_balance;
                    $totalAmountFinal = (int) $destionationBalance + (int) $amount;
                    $dataAmountFinal = array(
                        'total_balance' => $totalAmountFinal
                    );
                    $this->db->where('user_id', $userid);
                    $this->db->where('wallet_name', $destionationSelected);
                    $this->db->update('user_wallet', $dataAmountFinal);
                    $queryWallet = $this->db->select('total_balance')
                        ->from('user_wallet')
                        ->where('user_id', $userid)
                        ->where('wallet_name', $walletOption)
                        ->get();
                    $resultDeduct = $queryWallet->row();
                    $deductBalance = $resultDeduct->total_balance;
                    $totalAmountDeduct = (int) $deductBalance - (int) $amount;
                    $dataAmountDeduct = array(
                        'total_balance' => $totalAmountDeduct
                    );

                    $this->db->where('id', $walletid);
                    $this->db->update('user_wallet', $dataAmountDeduct);

                    // Laporan transaksi Cash-in
                    // laporan transaksi cash-out
                    // laporan summary
                    // var_dump($totalAmountFinal);
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed transaction!</div>');
                        redirect('financial/mywallet');
                    } else {
                        $this->db->trans_commit();
                        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Money has been moved</div>');
                        redirect('financial/mywallet');
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed transaction! Your balance not valid.</div>');
                    redirect('financial/mywallet');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed transaction! Wallet is Same.</div>');
                redirect('financial/mywallet');
            }
        }
    }

    public static function formatRupiah($angka)
    {
        $rupiah = number_format($angka, 0, ',', '.');
        return "Rp " . $rupiah;
    }
    // Cash in
    public function cashin()
    {
        $data['title'] = 'Cash-in';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $id = $data['user']['id'];
        $query = $this->db->select('uc.*, uil.income_name, uw.wallet_name')
            ->from('user_cashin uc')
            ->join('user_income_list uil', 'uc.user_id = uil.user_id AND uc.income_id = uil.id')
            ->join('user_wallet uw', 'uc.user_id = uw.user_id AND uc.wallet_id = uw.id')
            ->where('uc.user_id', $id)
            ->get();
        $data['cashin'] = $query->result_array();

        $data['category'] = $this->db->get_where('user_income_list', ['user_id' => $id])->result_array();
        $data['walletName'] = $this->db->get_where('user_wallet', ['user_id' => $id])->result_array();

        $this->form_validation->set_rules('date', 'Cash-in Date', 'required');
        $this->form_validation->set_rules('name', 'Cash-in Name', 'required');
        $this->form_validation->set_rules('category', 'Cash-in category', 'required');
        $this->form_validation->set_rules('wallet', 'Cash-in wallet', 'required');
        $this->form_validation->set_rules('amount', 'Cash-in amount', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('financial/cashin', $data);
            $this->load->view('templates/footer');
        } else {
            $date = $this->input->post('date');
            $amount = $this->input->post('amount');
            $incomeId = $this->input->post('category');
            $walletId = $this->input->post('wallet');
            $cashinName = $this->input->post('name');
            /** query wallet amount */
            $queryWalletAmount = "SELECT total_balance, wallet_name FROM user_wallet WHERE id = $walletId AND user_id = $id";
            $walletAmount = $this->db->query($queryWalletAmount)->row_array();
            if (isset($walletAmount)) {
                $lastAmount = $walletAmount['total_balance'];
                /** insert tbl_cashin */
                $dataCashin = [
                    'user_id' => $id,
                    'date' => $date,
                    'name' => $cashinName,
                    'income_id' => $incomeId,
                    'wallet_id' => $walletId,
                    'amount' => $amount,
                ];
                $this->db->insert('user_cashin', $dataCashin);

                /** update tbl_wallet_amount */
                $newAmount = (float) $lastAmount + (float) $amount;
                $dataWallet = [
                    'total_balance' => $newAmount
                ];

                $this->db->update('user_wallet', $dataWallet, ['id' => $walletId, 'user_id' => $id]);

                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Cahs-in added</div>');
                redirect('financial/cashin');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Check your Wallet, the wallet is not Exist.</div>');
                redirect('financial/cashin');
            }
        }

        // var_dump($data['cashin']);

    }
    public function openeditcashin($id)
    {
        $cashin['cashin'] = $this->db->get_where('user_cashin', ['id' => $id])->row();

        echo json_encode($cashin);
    }
    public function editcashin()
    {
        $this->form_validation->set_rules('date_new', 'Cash-in Date', 'required');
        $this->form_validation->set_rules('name_new', 'Cash-in Name', 'required');
        $this->form_validation->set_rules('category_new', 'Cash-in category', 'required');
        $this->form_validation->set_rules('walletNew', 'Cash-in wallet', 'required');
        $this->form_validation->set_rules('amount_new', 'Cash-in amount', 'required');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Cash-in edited failed!</div>');

            redirect('financial/cashin');
        } else {
            $email = $this->session->userdata('email');
            $query = $this->db->select('id')
                ->from('user')
                ->where('email', $email)
                ->get();
            $userid = $query->row();
            $cashId = $this->input->post('cashin_id');
            $date = $this->input->post('date_new');
            $name = $this->input->post('name_new');
            $category = $this->input->post('category_new');
            $wallet = $this->input->post('walletNew');
            $amount = $this->input->post('amount_new');
            $lastAmount = $this->input->post('last_amount');
            $lastWalletBalance = $this->input->post('last_wallet');


            /** query wallet amount */
            $queryWalletAmount = "SELECT total_balance, wallet_name FROM user_wallet WHERE id = $wallet AND user_id = $userid->id";
            $walletAmount = $this->db->query($queryWalletAmount)->row_array();

            $queryLastWalletAmount = "SELECT total_balance, wallet_name FROM user_wallet WHERE id = $lastWalletBalance AND user_id = $userid->id";
            $resultLastWalletAmount = $this->db->query($queryLastWalletAmount)->row_array();

            if (isset($walletAmount)) {
                $resLastWalletAmount = $walletAmount['total_balance'];
                $resLastWalletAmountAwal = $resultLastWalletAmount['total_balance'];

                $queryLastWallet = "SELECT wallet_id, amount FROM user_cashin WHERE id = $cashId";
                $lastWallet = $this->db->query($queryLastWallet)->row_array();
                $lastWalletAmount = $lastWallet['amount'];
                $dataCashin = [
                    'wallet_id' => $wallet,
                    'amount' => $amount,
                ];
                $this->db->update('user_cashin', $dataCashin, ['id' => $cashId]);



                if ($lastWallet['wallet_id'] != $wallet) {
                    // rset balance wallet sebelumnya
                    $newAmountWallet = (float) $resLastWalletAmountAwal - (float) $lastAmount;

                    $dataWallet = [
                        'total_balance' => $newAmountWallet
                    ];

                    $this->db->update('user_wallet', $dataWallet, ['id' => $lastWalletBalance, 'user_id' => $userid->id]);


                    /** move money */
                    $newBalance = (float)$resLastWalletAmount + (float)$amount;

                    $dataWallet2 = [
                        'total_balance' => $newBalance
                    ];

                    $this->db->update('user_wallet', $dataWallet2, ['id' => $wallet, 'user_id' => $userid->id]);


                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Cahs-in has been edited and move money to another wallet.</div>');
                    redirect('financial/cashin');
                } else {
                    // jika sama wallet
                    $penguranganAmount = (float)$lastWalletAmount - (float)$amount;

                    $newBalance = (float)$resLastWalletAmount - (float)$penguranganAmount;

                    $dataWallet = [
                        'total_balance' => $newBalance
                    ];

                    $this->db->update('user_wallet', $dataWallet, ['id' => $wallet, 'user_id' => $userid->id]);

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Cahs-in has been edited</div>');
                    redirect('financial/cashin');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Check your Wallet, the wallet is not Exist.</div>');
                redirect('financial/cashin');
            }
        }
    }
}
