<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $title; ?></title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url('assets'); ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo base_url('assets'); ?>/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('node_modules'); ?>/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <style>
        /* Mengubah warna latar belakang header tabel */
        #dataTable thead th {
            background-color: #f2f2f2;
        }

        /* Mengubah warna teks di sel header tabel */
        #dataTable thead th {
            color: #333;
        }

        /* Menghapus border di sel header tabel */
        #dataTable thead th {
            border: none;
        }

        /* Mengubah warna latar belakang baris ganjil */
        #dataTable tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        /* Ubah ukuran dan warna input pencarian */
        .custom-search {
            width: 200px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f2f2f2;
            color: #333;
        }

        /* Ubah warna latar belakang input saat diklik */
        .custom-search:focus {
            background-color: white;
        }

        /* Gaya untuk elemen 'Show entries' */
        .custom-filter {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #333;
        }

        /* Gaya untuk label 'Show' */
        .custom-filter label {
            margin-right: 10px;
        }

        /* Gaya untuk elemen 'select' */
        .custom-filter select {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f2f2f2;
            color: #333;
        }

        /* Gaya saat 'select' aktif (hover atau fokus) */
        .custom-filter select:hover,
        .custom-filter select:focus {
            background-color: white;
            outline: none;
        }

        /* Gaya untuk elemen pagination */
        .dataTables_paginate {
            text-align: center;
            /* Pusatkan elemen pagination */
            margin-top: 20px;
            /* Atur jarak atas */
        }

        /* Gaya untuk tombol navigasi (Previous dan Next) */
        .paginate_button {
            display: inline-block;
            padding: 5px 10px;
            margin: 2px;
            border: 1px solid #ccc;
            background-color: #f2f2f2;
            color: #333;
            text-decoration: none;
            border-radius: 3px;
            cursor: pointer;
            /* Ubah ikon kursor menjadi tangan ketika menghover tombol */
        }

        /* Gaya untuk tombol navigasi aktif (current) */
        .paginate_button.current {
            background-color: #333;
            color: #fff;
            border-color: #333;
        }

        /* Gaya untuk tombol navigasi yang dinonaktifkan (disabled) */
        .paginate_button.disabled {
            background-color: #f2f2f2;
            color: #ccc;
            border: 1px solid #ccc;
            cursor: default;
            /* Hindari tindakan ketika mengklik tombol yang dinonaktifkan */
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">