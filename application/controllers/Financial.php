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
        $data['title'] = 'Summary';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $id = $data['user']['id'];
        $cashin = $this->db->limit(10)->get_where('user_cashin', ['user_id' => $id])->result_array();
        $cashout = $this->db->limit(10)->get_where('user_cashout', ['user_id' => $id])->result_array();

        // Ambil data user_wallet

        // Buat associative array untuk memetakan wallet_id ke wallet_name

        foreach ($cashin as &$data1) {
            $data1['kategori'] = "cashin";
            $data1['class'] = "badge badge-success";
            $user_wallet = $this->db->get_where('user_wallet', ['user_id' => $id, 'id' => $data1['wallet_id']])->row_array();
            $data1['wallet_name'] = $user_wallet['wallet_name'];
        }

        foreach ($cashout as &$data2) {
            $data2['kategori'] = "cashout";
            $data2['class'] = "badge badge-danger";
            $user_wallet = $this->db->get_where('user_wallet', ['user_id' => $id, 'id' => $data1['wallet_id']])->row_array();
            $data2['wallet_name'] = $user_wallet['wallet_name'];
        }


        $combined_data = array_merge($cashin, $cashout);

        $data['cashflow_list'] = $combined_data;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('financial/index', $data);
        $this->load->view('templates/footer');
    }

    public function getDataSummary()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $id = $data['user']['id'];
        $hasilData['cashin'] = $this->db->get_where('user_cashin', ['user_id' => $id])->result_array();
        $cashin = $hasilData['cashin'];

        /** cashin summary grafik */
        $monthlyTotal = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthStr = str_pad($month, 2, '0', STR_PAD_LEFT); // Format bulan menjadi dua digit (misalnya, 01, 02, dst.)
            $monthlyTotal[] = 0;
        }

        foreach ($cashin as $item) {
            $date = $item['date'];
            $amount = $item['amount'];
            $month = date('n', strtotime($date)); // Mengambil bulan sebagai angka (1-12)

            $monthlyTotal[$month - 1] += $amount; // Mengakses indeks array sesuai bulan dan menambahkan jumlah
        }
        $maxIncome = max($monthlyTotal);
        $indexOfMaxIncome = array_search($maxIncome, $monthlyTotal);

        // Konversi indeks bulan menjadi nama bulan
        $monthNames = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        $monthWithMaxIncome = $monthNames[$indexOfMaxIncome];
        $hasilData['monthlyTotal_cashin'] = $monthlyTotal;

        if ($cashin === null) {
            $hasilData['message_cashin'] = 'Penghasilan terbesar Anda ada di bulan ' . '<b>' . $monthWithMaxIncome . '</b>';
        } else {
            $hasilData['message_cashin'] = 'Belum ada data yang kamu catat. Silahkan catat keuanganmu...';
        }
        /** end of cashin summary grafik */

        /** cashout summary grafik */
        $hasilData['cashout'] = $this->db->get_where('user_cashout', ['user_id' => $id])->result_array();
        $cashout = $hasilData['cashout'];

        $monthlyTotalCashout = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthStr = str_pad($month, 2, '0', STR_PAD_LEFT); // Format bulan menjadi dua digit (misalnya, 01, 02, dst.)
            $monthlyTotalCashout[] = 0;
        }

        foreach ($cashout as $item) {
            $date = $item['date'];
            $amount = $item['amount'];
            $month = date('n', strtotime($date)); // Mengambil bulan sebagai angka (1-12)

            $monthlyTotalCashout[$month - 1] += $amount; // Mengakses indeks array sesuai bulan dan menambahkan jumlah
        }
        $maxOutcome = max($monthlyTotalCashout);
        $indexOfMaxOutcome = array_search($maxOutcome, $monthlyTotalCashout);

        $monthWithMaxOutcome = $monthNames[$indexOfMaxOutcome];

        $hasilData['monthlyTotal_cashout'] = $monthlyTotalCashout;

        if ($cashout === null) {
            $hasilData['message_cashout'] = 'Pengeluaran terbesarmu ada di bulan ' . '<b>' . $monthWithMaxOutcome . '</b>';
        } else {
            $hasilData['message_cashout'] = 'Belum ada data yang kamu catat. Silahkan catat keuanganmu!';
        }
        /** end of cashout summary grafik */

        echo json_encode($hasilData);
        // print_r($hasilData);
    }
    public static function calculateTotalForDateRange($transactions, $startDate, $endDate)
    {
        $filteredTransactions = [];

        foreach ($transactions as $transaction) {
            $transactionDate = date('Y-m-d', strtotime($transaction['date']));

            // Mengubah tanggal menjadi objek DateTime
            $transactionDateTime = new DateTime($transactionDate);
            $startDateTime = new DateTime($startDate);
            $endDateTime = new DateTime($endDate);

            // Memeriksa apakah tanggal transaksi berada dalam rentang yang benar
            if ($transactionDateTime >= $startDateTime && $transactionDateTime <= $endDateTime) {
                $filteredTransactions[] = $transaction;
            }
        }

        $totalAmount = array_sum(array_column($filteredTransactions, 'amount'));
        return $totalAmount;
    }
    public function categoryCashin($user_id, $transactions, $startDate, $endDate)
    {
        $filteredTransactionsCategory = [];
        $filteredTransactionsAmount = [];
        $categoryIncomeTotals = []; // Initialize an array to store category income totals

        foreach ($transactions as $transaction) {
            $transactionDate = date('Y-m-d', strtotime($transaction['date']));

            // Mengubah tanggal menjadi objek DateTime
            $transactionDateTime = new DateTime($transactionDate);
            $startDateTime = new DateTime($startDate);
            $endDateTime = new DateTime($endDate);

            // Memeriksa apakah tanggal transaksi berada dalam rentang yang benar
            if ($transactionDateTime >= $startDateTime && $transactionDateTime <= $endDateTime) {
                $income = $this->db->get_where('user_income_list', ['user_id' => $user_id, 'id' => intval($transaction['income_id'])])->row_array();
                $categoryName = $income['income_name'];
                $categoryAmount = $transaction['amount'];

                // Add the amount to the category's total income
                if (!isset($categoryIncomeTotals[$categoryName])) {
                    $categoryIncomeTotals[$categoryName] = 0;
                }
                $categoryIncomeTotals[$categoryName] += $categoryAmount;

                $filteredTransactionsCategory[] = $categoryName;
                $filteredTransactionsAmount[] = $categoryAmount;
            }
        }

        $data['data'] = ['filteredTransactionsCategory' => $filteredTransactionsCategory, 'filteredTransactionsAmount' => $filteredTransactionsAmount];

        // Find the category with the highest income
        $highestIncomeCategory = '';
        $highestIncomeAmount = 0;

        foreach ($categoryIncomeTotals as $category => $total) {
            if ($total > $highestIncomeAmount) {
                $highestIncomeCategory = $category;
                $highestIncomeAmount = $total;
            }
        }

        $data['highestIncomeCategory'] = $highestIncomeCategory;
        $data['highestIncomeAmount'] = $highestIncomeAmount;
        return $data;
    }
    public function categoryCashout($user_id, $transactions, $startDate, $endDate)
    {
        $filteredTransactionsCategory = [];
        $filteredTransactionsAmount = [];
        $categoryCategoryTotals = []; // Initialize an array to store category income totals

        foreach ($transactions as $transaction) {
            $transactionDate = date('Y-m-d', strtotime($transaction['date']));

            // Mengubah tanggal menjadi objek DateTime
            $transactionDateTime = new DateTime($transactionDate);
            $startDateTime = new DateTime($startDate);
            $endDateTime = new DateTime($endDate);

            // Memeriksa apakah tanggal transaksi berada dalam rentang yang benar
            if ($transactionDateTime >= $startDateTime && $transactionDateTime <= $endDateTime) {
                $category = $this->db->get_where('user_category_list', ['user_id' => $user_id, 'id' => intval($transaction['category_id'])])->row_array();
                $categoryName = $category['category_name'];
                $categoryAmount = $transaction['amount'];

                // Add the amount to the category's total income
                if (!isset($categoryCategoryTotals[$categoryName])) {
                    $categoryCategoryTotals[$categoryName] = 0;
                }
                $categoryCategoryTotals[$categoryName] += $categoryAmount;

                $filteredTransactionsCategory[] = $categoryName;
                $filteredTransactionsAmount[] = $categoryAmount;
            }
        }

        $data['data'] = ['filteredTransactionsCategory' => $filteredTransactionsCategory, 'filteredTransactionsAmount' => $filteredTransactionsAmount];

        // Find the category with the highest income
        $highestCategoryCategory = '';
        $highestCategoryAmount = 0;

        foreach ($categoryCategoryTotals as $category => $total) {
            if ($total > $highestCategoryAmount) {
                $highestCategoryCategory = $category;
                $highestCategoryAmount = $total;
            }
        }

        $data['highestCategoryCategory'] = $highestCategoryCategory;
        $data['highestCategoryAmount'] = $highestCategoryAmount;
        return $data;
    }
    public function generateCategoryColor($category)
    {
        $backgroundColor = [];
        $hoverBackgroundColor = [];
        foreach ($category as $color) {
            // Generate random colors in hexadecimal format
            $bgColor = '#' . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) .
                str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) .
                str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);

            $hoverBgColor = '#' . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) .
                str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) .
                str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);

            // Store the generated colors in arrays
            $backgroundColor[] = $bgColor;
            $hoverBackgroundColor[] = $hoverBgColor;
        }
        $data['backgroundColor'] = $backgroundColor;
        $data['hoverColor'] = $hoverBackgroundColor;
        return $data;
    }
    public function filteredCategoryGraphic()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $id = $data['user']['id'];
        $hasilData['cashin'] = $this->db->get_where('user_cashin', ['user_id' => $id])->result_array();
        $cashin = $hasilData['cashin'];
        $hasilData['cashout'] = $this->db->get_where('user_cashout', ['user_id' => $id])->result_array();
        $cashout = $hasilData['cashout'];
        // contoh code
        // Tanggal custom yang ingin Anda hitung (misalnya 3 bulan terakhir)
        // declare pilihan 1,3,custom
        $pilihWaktu = $_GET['pickMonth'];
        if ($pilihWaktu === '1 Month') {
            $endDateOne = date('Y-m-d'); // Tanggal saat ini
            $startDateOne = date('Y-m-d', strtotime('-1 months', strtotime($endDateOne))); // 3 bulan sebelumnya
            // Menghitung total cashin dan cashout untuk rentang tanggal yang telah ditentukan
            $totalCashin = $this->calculateTotalForDateRange($cashin, $startDateOne, $endDateOne);
            $totalCashout = $this->calculateTotalForDateRange($cashout, $startDateOne, $endDateOne);
            $selisihCash = floatval($totalCashin) - floatval($totalCashout);
            // Menentukan pesan berdasarkan perbandingan

            if ($totalCashout > 0.5 * $totalCashin) {
                $message = "Status keuangan Anda buruk";
            } else {
                $message = "Status keuangan Anda baik";
            }
            // Mengembalikan hasil dalam format JSON
            $result = [
                'start_date' => $startDateOne,
                'end_date' => $endDateOne,
                'total_cashin' => $totalCashin,
                'selisih_cashin' => $selisihCash,
                'total_cashout' => $totalCashout,
                'message' => $message,
            ];

            echo json_encode($result);
        } elseif ($pilihWaktu === '3 Month') {
            $endDateTiga = date('Y-m-d'); // Tanggal saat ini
            $startDateTiga = date('Y-m-d', strtotime('-3 months', strtotime($endDateTiga)));
            // Menghitung total cashin dan cashout untuk rentang tanggal yang telah ditentukan
            $totalCashin = $this->calculateTotalForDateRange($cashin, $startDateTiga, $endDateTiga);
            $totalCashout = $this->calculateTotalForDateRange($cashout, $startDateTiga, $endDateTiga);
            $selisihCash = floatval($totalCashin) - floatval($totalCashout);
            // Menentukan pesan berdasarkan perbandingan
            if ($totalCashout > 0.5 * $totalCashin) {
                $message = "Status keuangan Anda buruk";
            } else {
                $message = "Status keuangan Anda baik";
            }
            // Mengembalikan hasil dalam format JSON
            $result = [
                'start_date' => $startDateTiga,
                'end_date' => $endDateTiga,
                'total_cashin' => $totalCashin,
                'selisih_cashin' => $selisihCash,
                'total_cashout' => $totalCashout,
                'message' => $message,
            ];

            echo json_encode($result);
        } else if ($pilihWaktu === 'custom_date') {
            $endDate = $_GET['endDate'];
            $startDate = $_GET['startDate'];

            // Menghitung total cashin dan cashout untuk rentang tanggal yang telah ditentukan
            $totalCashin = $this->calculateTotalForDateRange($cashin, $startDate, $endDate);
            $totalCashout = $this->calculateTotalForDateRange($cashout, $startDate, $endDate);
            $selisihCash = floatval($totalCashin) - floatval($totalCashout);
            // Menentukan pesan berdasarkan perbandingan
            if ($totalCashout > 0.5 * $totalCashin) {
                $message = "Status keuangan Anda buruk";
            } else {
                $message = "Status keuangan Anda baik";
            }
            // Mengembalikan hasil dalam format JSON
            $result = [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_cashin' => $totalCashin,
                'selisih_cashin' => $selisihCash,
                'total_cashout' => $totalCashout,
                'message' => $message,
            ];

            echo json_encode($result);
        }
    }
    public function filteredCashinGraphic()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $id = $data['user']['id'];
        $hasilData['cashin'] = $this->db->get_where('user_cashin', ['user_id' => $id])->result_array();
        $cashin = $hasilData['cashin'];

        $pilihWaktu = $_GET['pickMonth'];

        if ($pilihWaktu === '1 Month') {
            $endDateOne = date('Y-m-d'); // Tanggal saat ini
            $startDateOne = date('Y-m-d', strtotime('-1 months', strtotime($endDateOne)));
            $totalCashin = $this->calculateTotalForDateRange($cashin, $startDateOne, $endDateOne);
            $arrayCashin = $this->categoryCashin($id, $cashin, $startDateOne, $endDateOne);
            $filteredCategory = $arrayCashin['data']['filteredTransactionsCategory'];
            $filteredAmount = $arrayCashin['data']['filteredTransactionsAmount'];

            $categoryTotals = []; // Inisialisasi array untuk menyimpan total uang per kategori
            $resultArrayCategory = [];
            $resultArrayCashinCategory = [];

            foreach ($filteredCategory as $index => $category) {
                $cashin = intval($filteredAmount[$index]); // Mengonversi jumlah uang menjadi integer

                // Memeriksa apakah kategori sudah ada dalam $categoryTotals
                if (isset($categoryTotals[$category])) {
                    // Jika sudah ada, tambahkan jumlah uang ke total yang sudah ada
                    $categoryTotals[$category] += $cashin;
                } else {
                    // Jika belum ada, inisialisasi total untuk kategori tersebut
                    $categoryTotals[$category] = $cashin;
                }
            }

            // Mengisi hasil ke array yang sesuai
            foreach ($categoryTotals as $category => $total) {
                $resultArrayCategory[] = $category;
                $resultArrayCashinCategory[] = strval($total); // Mengonversi total kategori menjadi string
            }
            $generateColor = $this->generateCategoryColor($resultArrayCategory);

            if ($totalCashin === 0) {
                $message = 'Belum ada data yang kamu catat.';
            } else {
                $message = $arrayCashin['highestIncomeCategory'] . ' menjadi penghasilan terbesarmu, dengan nilai Rp. ' . $arrayCashin['highestIncomeAmount'] . ' , semangat kerjanya kawan!';
            }
            $result = [
                'start_date' => $startDateOne,
                'end_date' => $endDateOne,
                'total_cashin' => $totalCashin,
                'arrayCashinCategory' => $resultArrayCashinCategory,
                'arrayCategory' => $resultArrayCategory,
                'highestIncomeCategory' => $arrayCashin['highestIncomeCategory'],
                'highestIncomeAmount' => $arrayCashin['highestIncomeAmount'],
                'backgroundColor' => $generateColor['backgroundColor'],
                'hoverColor' => $generateColor['hoverColor'],
                'message' => $message,
            ];
        } else if ($pilihWaktu === '3 Month') {
            $endDateOne = date('Y-m-d'); // Tanggal saat ini
            $startDateOne = date('Y-m-d', strtotime('-3 months', strtotime($endDateOne)));
            $totalCashin = $this->calculateTotalForDateRange($cashin, $startDateOne, $endDateOne);
            $arrayCashin = $this->categoryCashin($id, $cashin, $startDateOne, $endDateOne);
            $filteredCategory = $arrayCashin['data']['filteredTransactionsCategory'];
            $filteredAmount = $arrayCashin['data']['filteredTransactionsAmount'];

            $categoryTotals = []; // Inisialisasi array untuk menyimpan total uang per kategori
            $resultArrayCategory = [];
            $resultArrayCashinCategory = [];

            foreach ($filteredCategory as $index => $category) {
                $cashin = intval($filteredAmount[$index]); // Mengonversi jumlah uang menjadi integer

                // Memeriksa apakah kategori sudah ada dalam $categoryTotals
                if (isset($categoryTotals[$category])) {
                    // Jika sudah ada, tambahkan jumlah uang ke total yang sudah ada
                    $categoryTotals[$category] += $cashin;
                } else {
                    // Jika belum ada, inisialisasi total untuk kategori tersebut
                    $categoryTotals[$category] = $cashin;
                }
            }

            // Mengisi hasil ke array yang sesuai
            foreach ($categoryTotals as $category => $total) {
                $resultArrayCategory[] = $category;
                $resultArrayCashinCategory[] = strval($total); // Mengonversi total kategori menjadi string
            }
            $generateColor = $this->generateCategoryColor($resultArrayCategory);

            if ($totalCashin === 0) {
                $message = 'Belum ada data yang kamu catat.';
            } else {
                $message = $arrayCashin['highestIncomeCategory'] . ' menjadi penghasilan terbesarmu, dengan nilai Rp. ' . $arrayCashin['highestIncomeAmount'] . ' , semangat kerjanya kawan!';
            }
            $result = [
                'start_date' => $startDateOne,
                'end_date' => $endDateOne,
                'total_cashin' => $totalCashin,
                'arrayCashinCategory' => $resultArrayCashinCategory,
                'arrayCategory' => $resultArrayCategory,
                'highestIncomeCategory' => $arrayCashin['highestIncomeCategory'],
                'highestIncomeAmount' => $arrayCashin['highestIncomeAmount'],
                'backgroundColor' => $generateColor['backgroundColor'],
                'hoverColor' => $generateColor['hoverColor'],
                'message' => $message
            ];
        } else if ($pilihWaktu === 'custom_date') {
            $endDateOne = $_GET['endDate'];
            $startDateOne = $_GET['startDate'];
            $totalCashin = $this->calculateTotalForDateRange($cashin, $startDateOne, $endDateOne);
            $arrayCashin = $this->categoryCashin($id, $cashin, $startDateOne, $endDateOne);
            $filteredCategory = $arrayCashin['data']['filteredTransactionsCategory'];
            $filteredAmount = $arrayCashin['data']['filteredTransactionsAmount'];

            $categoryTotals = []; // Inisialisasi array untuk menyimpan total uang per kategori
            $resultArrayCategory = [];
            $resultArrayCashinCategory = [];

            foreach ($filteredCategory as $index => $category) {
                $cashin = intval($filteredAmount[$index]); // Mengonversi jumlah uang menjadi integer

                // Memeriksa apakah kategori sudah ada dalam $categoryTotals
                if (isset($categoryTotals[$category])) {
                    // Jika sudah ada, tambahkan jumlah uang ke total yang sudah ada
                    $categoryTotals[$category] += $cashin;
                } else {
                    // Jika belum ada, inisialisasi total untuk kategori tersebut
                    $categoryTotals[$category] = $cashin;
                }
            }

            // Mengisi hasil ke array yang sesuai
            foreach ($categoryTotals as $category => $total) {
                $resultArrayCategory[] = $category;
                $resultArrayCashinCategory[] = strval($total); // Mengonversi total kategori menjadi string
            }
            $generateColor = $this->generateCategoryColor($resultArrayCategory);

            if ($totalCashin === 0) {
                $message = 'Belum ada data yang kamu catat.';
            } else {
                $message =  $arrayCashin['highestIncomeCategory'] . ' menjadi penghasilan terbesarmu, dengan nilai Rp. ' . $arrayCashin['highestIncomeAmount'] . ' , semangat kerjanya kawan!';
            }
            $result = [
                'start_date' => $startDateOne,
                'end_date' => $endDateOne,
                'total_cashin' => $totalCashin,
                'arrayCashinCategory' => $resultArrayCashinCategory,
                'arrayCategory' => $resultArrayCategory,
                'highestIncomeCategory' => $arrayCashin['highestIncomeCategory'],
                'highestIncomeAmount' => $arrayCashin['highestIncomeAmount'],
                'backgroundColor' => $generateColor['backgroundColor'],
                'hoverColor' => $generateColor['hoverColor'],
                'message' => $message
            ];
        }
        echo json_encode($result);
    }
    public function filteredCashoutGraphic()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $id = $data['user']['id'];
        $hasilData['cashout'] = $this->db->get_where('user_cashout', ['user_id' => $id])->result_array();
        $cashout = $hasilData['cashout'];

        $pilihWaktu = $_GET['pickMonth'];

        if ($pilihWaktu === '1 Month') {
            $endDateOne = date('Y-m-d'); // Tanggal saat ini
            $startDateOne = date('Y-m-d', strtotime('-1 months', strtotime($endDateOne)));
            $totalCashout = $this->calculateTotalForDateRange($cashout, $startDateOne, $endDateOne);
            $arrayCashout = $this->categoryCashout($id, $cashout, $startDateOne, $endDateOne);
            $filteredCategory = $arrayCashout['data']['filteredTransactionsCategory'];
            $filteredAmount = $arrayCashout['data']['filteredTransactionsAmount'];

            $categoryTotals = []; // Inisialisasi array untuk menyimpan total uang per kategori
            $resultArrayCategory = [];
            $resultArrayCashoutCategory = [];

            foreach ($filteredCategory as $index => $category) {
                $cashin = intval($filteredAmount[$index]); // Mengonversi jumlah uang menjadi integer

                // Memeriksa apakah kategori sudah ada dalam $categoryTotals
                if (isset($categoryTotals[$category])) {
                    // Jika sudah ada, tambahkan jumlah uang ke total yang sudah ada
                    $categoryTotals[$category] += $cashin;
                } else {
                    // Jika belum ada, inisialisasi total untuk kategori tersebut
                    $categoryTotals[$category] = $cashin;
                }
            }

            // Mengisi hasil ke array yang sesuai
            foreach ($categoryTotals as $category => $total) {
                $resultArrayCategory[] = $category;
                $resultArrayCashoutCategory[] = strval($total); // Mengonversi total kategori menjadi string
            }
            $generateColor = $this->generateCategoryColor($resultArrayCategory);
            if ($totalCashout === 0) {
                $message = 'Belum ada data yang kamu catat.';
            } else {
                $message = $arrayCashout['highestCategoryCategory'] . ' menjadi pengeluaran terbesarmu, dengan nilai Rp. ' . $arrayCashout['highestCategoryAmount'] . ' , semangat kerjanya kawan!';
            }
            $result = [
                'start_date' => $startDateOne,
                'end_date' => $endDateOne,
                'total_cashout' => $totalCashout,
                'arrayCashoutCategory' => $resultArrayCashoutCategory,
                'arrayCategory' => $resultArrayCategory,
                'highestCategoryCategory' => $arrayCashout['highestCategoryCategory'],
                'highestCategoryAmount' => $arrayCashout['highestCategoryAmount'],
                'backgroundColor' => $generateColor['backgroundColor'],
                'hoverColor' => $generateColor['hoverColor'],
                'message' => $message
            ];
        } else if ($pilihWaktu === '3 Month') {
            $endDateOne = date('Y-m-d'); // Tanggal saat ini
            $startDateOne = date('Y-m-d', strtotime('-3 months', strtotime($endDateOne)));
            $totalCashout = $this->calculateTotalForDateRange($cashout, $startDateOne, $endDateOne);
            $arrayCashout = $this->categoryCashout($id, $cashout, $startDateOne, $endDateOne);
            $filteredCategory = $arrayCashout['data']['filteredTransactionsCategory'];
            $filteredAmount = $arrayCashout['data']['filteredTransactionsAmount'];

            $categoryTotals = []; // Inisialisasi array untuk menyimpan total uang per kategori
            $resultArrayCategory = [];
            $resultArrayCashoutCategory = [];

            foreach ($filteredCategory as $index => $category) {
                $cashin = intval($filteredAmount[$index]); // Mengonversi jumlah uang menjadi integer

                // Memeriksa apakah kategori sudah ada dalam $categoryTotals
                if (isset($categoryTotals[$category])) {
                    // Jika sudah ada, tambahkan jumlah uang ke total yang sudah ada
                    $categoryTotals[$category] += $cashin;
                } else {
                    // Jika belum ada, inisialisasi total untuk kategori tersebut
                    $categoryTotals[$category] = $cashin;
                }
            }

            // Mengisi hasil ke array yang sesuai
            foreach ($categoryTotals as $category => $total) {
                $resultArrayCategory[] = $category;
                $resultArrayCashoutCategory[] = strval($total); // Mengonversi total kategori menjadi string
            }
            $generateColor = $this->generateCategoryColor($resultArrayCategory);

            if ($totalCashout === 0) {
                $message = 'Belum ada data yang kamu catat.';
            } else {
                $message =  $arrayCashout['highestCategoryCategory'] . ' menjadi pengeluaran terbesarmu, dengan nilai Rp. ' . $arrayCashout['highestCategoryAmount'] . ' , semangat kerjanya kawan!';
            }
            $result = [
                'start_date' => $startDateOne,
                'end_date' => $endDateOne,
                'total_cashout' => $totalCashout,
                'arrayCashoutCategory' => $resultArrayCashoutCategory,
                'arrayCategory' => $resultArrayCategory,
                'highestCategoryCategory' => $arrayCashout['highestCategoryCategory'],
                'highestCategoryAmount' => $arrayCashout['highestCategoryAmount'],
                'backgroundColor' => $generateColor['backgroundColor'],
                'hoverColor' => $generateColor['hoverColor'],
                'message' => $message
            ];
        } else if ($pilihWaktu === 'custom_date') {
            $endDateOne = $_GET['endDate'];
            $startDateOne = $_GET['startDate'];
            $totalCashout = $this->calculateTotalForDateRange($cashout, $startDateOne, $endDateOne);
            $arrayCashout = $this->categoryCashout($id, $cashout, $startDateOne, $endDateOne);
            $filteredCategory = $arrayCashout['data']['filteredTransactionsCategory'];
            $filteredAmount = $arrayCashout['data']['filteredTransactionsAmount'];

            $categoryTotals = []; // Inisialisasi array untuk menyimpan total uang per kategori
            $resultArrayCategory = [];
            $resultArrayCashoutCategory = [];

            foreach ($filteredCategory as $index => $category) {
                $cashin = intval($filteredAmount[$index]); // Mengonversi jumlah uang menjadi integer

                // Memeriksa apakah kategori sudah ada dalam $categoryTotals
                if (isset($categoryTotals[$category])) {
                    // Jika sudah ada, tambahkan jumlah uang ke total yang sudah ada
                    $categoryTotals[$category] += $cashin;
                } else {
                    // Jika belum ada, inisialisasi total untuk kategori tersebut
                    $categoryTotals[$category] = $cashin;
                }
            }

            // Mengisi hasil ke array yang sesuai
            foreach ($categoryTotals as $category => $total) {
                $resultArrayCategory[] = $category;
                $resultArrayCashoutCategory[] = strval($total); // Mengonversi total kategori menjadi string
            }
            $generateColor = $this->generateCategoryColor($resultArrayCategory);

            if ($totalCashout === 0) {
                $message = 'Belum ada data yang kamu catat.';
            } else {
                $message =  $arrayCashout['highestCategoryCategory'] . ' menjadi pengeluaran terbesarmu, dengan nilai Rp. ' . $arrayCashout['highestCategoryAmount'] . ' , semangat kerjanya kawan!';
            }
            $result = [
                'start_date' => $startDateOne,
                'end_date' => $endDateOne,
                'total_cashout' => $totalCashout,
                'arrayCashoutCategory' => $resultArrayCashoutCategory,
                'arrayCategory' => $resultArrayCategory,
                'highestCategoryCategory' => $arrayCashout['highestCategoryCategory'],
                'highestCategoryAmount' => $arrayCashout['highestCategoryAmount'],
                'backgroundColor' => $generateColor['backgroundColor'],
                'hoverColor' => $generateColor['hoverColor'],
                'message' => $message
            ];
        }
        echo json_encode($result);
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

        $totalBalance = 0;
        if (isset($data['myWallet'])) {
            foreach ($data['myWallet'] as $wallet) {
                $totalBalance += intval($wallet["total_balance"]);
            }
        }
        $data['total_balance'] = (int) $totalBalance;

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

        $total_balance = 0;
        if (isset($data['cashin'])) {
            foreach ($data['cashin'] as $cashin) {
                $total_balance += intval($cashin["amount"]);
            }
        }
        $data['total_cashin'] = (int) $total_balance;


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

    public function deletecashin($id)
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $userId = $data['user']['id'];

        $queryCashin = $this->db->select('uc.*, uil.income_name, uw.wallet_name')
            ->from('user_cashin uc')
            ->join('user_income_list uil', 'uc.user_id = uil.user_id AND uc.income_id = uil.id')
            ->join('user_wallet uw', 'uc.user_id = uw.user_id AND uc.wallet_id = uw.id')
            ->where('uc.id', $id)
            ->get();

        $dataCashin = $queryCashin->row_array();

        $walletId = $dataCashin['wallet_id'];
        /** query wallet amount */
        $queryWalletAmount = "SELECT total_balance, wallet_name FROM user_wallet WHERE id = $walletId AND user_id = $userId";
        $walletAmount = $this->db->query($queryWalletAmount)->row_array();

        if (isset($walletAmount)) {
            $lastAmount = $walletAmount['total_balance'];
            $amount = $dataCashin['amount'];

            /** update tbl_wallet_amount */
            $newAmount = (float) $lastAmount - (float) $amount;

            $dataWallet = [
                'total_balance' => $newAmount
            ];

            $this->db->update('user_wallet', $dataWallet, ['id' => $walletId, 'user_id' => $userId]);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Cahs-in added</div>');
        }

        $this->db->where('id', $id);
        $this->db->delete('user_cashin');
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Cash-in has been deleted</div>');

        redirect('financial/cashin');
    }
    public function cashout()
    {
        $data['title'] = 'Cash-out';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $id = $data['user']['id'];

        $query = $this->db->select('uc.*, ucl.category_name, uw.wallet_name')
            ->from('user_cashout uc')
            ->join('user_category_list ucl', 'uc.user_id = ucl.user_id AND uc.category_id = ucl.id')
            ->join('user_wallet uw', 'uc.user_id = uw.user_id AND uc.wallet_id = uw.id')
            ->where('uc.user_id', $id)
            ->get();
        $data['cashout'] = $query->result_array();

        $total_balance = 0;
        if (isset($data['cashout'])) {
            foreach ($data['cashout'] as $cashout) {
                $total_balance += intval($cashout["amount"]);
            }
        }
        $data['total_cashout'] = (int) $total_balance;
        $data['category'] = $this->db->get_where('user_category_list', ['user_id' => $id])->result_array();
        $data['walletName'] = $this->db->get_where('user_wallet', ['user_id' => $id])->result_array();

        $this->form_validation->set_rules('date', 'Cash-out Date', 'required');
        $this->form_validation->set_rules('name', 'Cash-out Name', 'required');
        $this->form_validation->set_rules('category', 'Cash-out category', 'required');
        $this->form_validation->set_rules('wallet', 'Cash-out wallet', 'required');
        $this->form_validation->set_rules('amount', 'Cash-out amount', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('financial/cashout', $data);
            $this->load->view('templates/footer');
        } else {
            $date = $this->input->post('date');
            $amount = $this->input->post('amount');
            $outcomeId = $this->input->post('category');
            $walletId = $this->input->post('wallet');
            $cashoutName = $this->input->post('name');

            /** query wallet amount */
            $queryWalletAmount = "SELECT total_balance, wallet_name FROM user_wallet WHERE id = $walletId AND user_id = $id";
            $walletAmount = $this->db->query($queryWalletAmount)->row_array();

            if (isset($walletAmount)) {
                $lastAmount = $walletAmount['total_balance'];
                $dataCashout = [
                    'user_id' => $id,
                    'date' => $date,
                    'name' => $cashoutName,
                    'category_id' => $outcomeId,
                    'wallet_id' => $walletId,
                    'amount' => $amount,
                ];

                $this->db->insert('user_cashout', $dataCashout);

                /** update tbl_wallet_amount */
                $newAmount = (float) $lastAmount - (float) $amount;
                $dataWallet = [
                    'total_balance' => $newAmount
                ];

                $this->db->update('user_wallet', $dataWallet, ['id' => $walletId, 'user_id' => $id]);
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Cahs-out added</div>');
                redirect('financial/cashout');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Check your Wallet, the wallet is not Exist.</div>');
                redirect('financial/cashout');
            }
        }
    }

    public function openeditcashout($id)
    {
        $cashin['cashout'] = $this->db->get_where('user_cashout', ['id' => $id])->row();

        echo json_encode($cashin);
    }
    public function editcashout()
    {
        $this->form_validation->set_rules('date_new', 'Cash-out Date', 'required');
        $this->form_validation->set_rules('name_new', 'Cash-out Name', 'required');
        $this->form_validation->set_rules('category_new', 'Cash-out category', 'required');
        $this->form_validation->set_rules('walletNew', 'Cash-out wallet', 'required');
        $this->form_validation->set_rules('amount_new', 'Cash-out amount', 'required');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Cash-out edited failed!</div>');

            redirect('financial/cashout');
        } else {
            $email = $this->session->userdata('email');
            $query = $this->db->select('id')
                ->from('user')
                ->where('email', $email)
                ->get();
            $userid = $query->row();
            $cashId = $this->input->post('cashout_id');
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

                $queryLastWallet = "SELECT wallet_id, amount FROM user_cashout WHERE id = $cashId";
                $lastWallet = $this->db->query($queryLastWallet)->row_array();
                $lastWalletAmount = $lastWallet['amount'];
                $dataCashout = [
                    'wallet_id' => $wallet,
                    'amount' => $amount,
                ];
                $this->db->update('user_cashout', $dataCashout, ['id' => $cashId]);

                if ($lastWallet['wallet_id'] != $wallet) {
                    // rset balance wallet sebelumnya
                    $newAmountWallet = (float) $resLastWalletAmountAwal + (float) $lastAmount;

                    $dataWallet = [
                        'total_balance' => $newAmountWallet
                    ];

                    $this->db->update('user_wallet', $dataWallet, ['id' => $lastWalletBalance, 'user_id' => $userid->id]);


                    /** move money */
                    $newBalance = (float)$resLastWalletAmount - (float)$amount;

                    $dataWallet2 = [
                        'total_balance' => $newBalance
                    ];

                    $this->db->update('user_wallet', $dataWallet2, ['id' => $wallet, 'user_id' => $userid->id]);


                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Cahs-out has been edited and move money to another wallet.</div>');
                    redirect('financial/cashout');
                } else {
                    // jika sama wallet
                    $penguranganAmount = (float)$lastWalletAmount - (float)$amount;

                    $newBalance = (float)$resLastWalletAmount + (float)$penguranganAmount;

                    $dataWallet = [
                        'total_balance' => $newBalance
                    ];

                    $this->db->update('user_wallet', $dataWallet, ['id' => $wallet, 'user_id' => $userid->id]);

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Cahs-out has been edited</div>');
                    redirect('financial/cashout');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Check your Wallet, the wallet is not Exist.</div>');
                redirect('financial/cashout');
            }
        }
    }

    public function deletecashout($id)
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $userId = $data['user']['id'];

        $query = $this->db->select('uc.*, ucl.category_name, uw.wallet_name')
            ->from('user_cashout uc')
            ->join('user_category_list ucl', 'uc.user_id = ucl.user_id AND uc.category_id = ucl.id')
            ->join('user_wallet uw', 'uc.user_id = uw.user_id AND uc.wallet_id = uw.id')
            ->where('uc.user_id', $userId)
            ->get();

        $dataCashout = $query->row_array();

        $walletId = $dataCashout['wallet_id'];
        /** query wallet amount */
        $queryWalletAmount = "SELECT total_balance, wallet_name FROM user_wallet WHERE id = '$walletId' AND user_id = '$userId'";

        $walletAmount = $this->db->query($queryWalletAmount)->row_array();

        if (isset($walletAmount)) {
            $lastAmount = $walletAmount['total_balance'];
            $amount = $dataCashout['amount'];

            /** update tbl_wallet_amount */
            $newAmount = (float) $lastAmount + (float) $amount;

            $dataWallet = [
                'total_balance' => $newAmount
            ];

            $this->db->update('user_wallet', $dataWallet, ['id' => $walletId, 'user_id' => $userId]);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Cahs-out added</div>');
        }

        $this->db->where('id', $id);
        $this->db->delete('user_cashout');
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Cash-out has been deleted</div>');

        redirect('financial/cashout');
    }
}
