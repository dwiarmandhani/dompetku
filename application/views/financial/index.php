<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?php echo $title; ?></h1>

    <div class="row">

        <!-- Chart pemasukan pengeluaran Chart -->
        <div class="col-xl-8 col-lg-8">
            <!-- Area Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="row">
                        <div class="btn btn-light btn_cashin active" onclick="btnCashin()">
                            <h6 class="m-0 font-weight-bold text-primary">Cash-in</h6>
                        </div>
                        <div class="btn btn-light btn_cashout" onclick="btnCashout()">
                            <h6 class="m-0 font-weight-bold text-primary">Cash-out</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="height: 500px; display: flex; flex-direction: column; justify-content: center;">
                    <div class="chart-area">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <canvas id="myAreaChart" style="display: block; width: 664px; height: 320px;" width="664" height="320" class="chartjs-render-monitor"></canvas>
                    </div>
                    <p id="caption_myAreaChart"></p>
                </div>
            </div>
        </div>

        <!-- Kategori pengeluaran Chart -->
        <div class="col-xl-4 col-lg-4" id="chart-pie-container">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="row justify-content-between">
                        <div class="btn btn-light">
                            <h6 class="m-0 font-weight-bold text-primary" id="label-container">Cashflow Health</h6>
                            <input type="hidden" id="value-filtered-chart" value="Cashflow Health">
                        </div>

                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                Filter
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" id="btn_health" href="#">Cashflow Health</a>
                                <a class="dropdown-item" id="btn_cashinflow" href="#">Cash-in Flow</a>
                                <a class="dropdown-item" id="btn_cashoutflow" href="#">Cash-out Flow</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body" style="height: 500px;">
                    <div class="row justify-content-end" id="filter_date">
                        <input type="hidden" id="value_pick" value="1 Month">
                        <div class="btn btn-light active" id="btn_satu_bulan">
                            <p class="m-0 font-weight-bold text-primary">1 Month</p>
                        </div>
                        <div class="btn btn-light ml-1 " id="btn_tiga_bulan" onclick="">
                            <p class="m-0 font-weight-bold text-primary">3 Months</p>
                        </div>
                        <div class="btn btn-light ml-1 " id="btn_custom_bulan" onclick="" data-toggle="modal" data-target="#datePickerModal">
                            <p class="m-0 font-weight-bold text-primary fa fa-calendar"></p>
                        </div>
                    </div>
                    <div class="pt-2">
                        <h5 class="text-capitalize" id="text-total"></h5>
                    </div>
                    <div class="chart-pie pt-1" id="container--chart">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <canvas id="myPieChart" width="299" height="253" style="display: block; width: 299px; height: 253px;" class="chartjs-render-monitor">
                        </canvas>
                    </div>
                    <p id="caption_pie"></p>
                </div>

            </div>
        </div>
    </div>
</div>
<?php if (isset($cashflow_list)) { ?>
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Cashflow List</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;">
                                    <thead>
                                        <tr role="row">
                                            <th class="sorting sorting_desc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="" aria-sort="descending" style="width: 50px;">No</th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="" style="width: 100px;">Date</th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="" style="width: 150px;">Name</th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="" style="width: 114px;">Type</th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="" style="width: 200px;">Description</th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="" style="width: 107px;">Wallet</th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="" style="width: 96px;">Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($cashflow_list as $data) { ?>
                                            <tr class="odd">
                                                <td class="sorting_1"><?php echo $i++; ?></td>
                                                <td><?php echo $data['date']; ?></td>
                                                <td><?php echo $data['name']; ?></td>
                                                <td><a href="#" class="<?php echo $data['class']; ?>">
                                                        <?php echo $data['kategori']; ?>
                                                    </a></td>
                                                <td><?php echo $data['keterangan']; ?></td>
                                                <td class=""><?php echo $data['wallet_name']; ?></td>
                                                <td><?php echo $data['amount']; ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- date picker -->

<div class="modal fade" id="datePickerModal" tabindex="-1" role="dialog" aria-labelledby="datePickerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="datePickerModalLabel">Pick start and end date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="startDate">Start Date<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="startDate" placeholder="Start Date..">
                </div>
                <div class="form-group">
                    <label for="endDate">End Date<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="endDate" placeholder="End Date..">
                </div>
            </div>
            <div class="modal-footer" id="submit_pick">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btn_simpan_pick" data-dismiss="modal">Simpan</button>
            </div>
        </div>
    </div>
</div>