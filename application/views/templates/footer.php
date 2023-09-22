<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class=" container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; Dompetku <?php echo date('Y', $user['date_created']); ?></span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="<?php echo base_url('auth/logout'); ?>">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="<?php echo base_url('assets'); ?>/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo base_url('assets'); ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?php echo base_url('assets'); ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?php echo base_url('assets'); ?>/js/sb-admin-2.min.js"></script>
<!-- Page level plugins -->
<script src="<?php echo base_url('assets'); ?>/vendor/chart.js/Chart.min.js"></script>
<script src="<?php echo base_url('node_modules'); ?>/sweetalert2/dist/sweetalert2.all.min.js"></script>
<script>
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass('selected').html(fileName);
    });
    $('.checklistRole').on('click', function() {
        const menuId = $(this).data('menu');
        const roleId = $(this).data('role');

        $.ajax({
            url: "<?= base_url('admin/changeaccess'); ?>",
            type: "POST",
            data: {
                menuId: menuId,
                roleId: roleId
            },
            success: function() {
                document.location.href = "<?= base_url('admin/roleaccess/'); ?>" + roleId;
            }
        });
    });

    $('.editMenu').on('click', function() {
        const menuId = $(this).data('id');

        $.ajax({
            url: "<?php echo base_url('menu/openedit/') ?>" + menuId,
            type: "GET",
            data: {
                id: menuId
            },
            dataType: "json",
            success: function(data) {
                // $('#editMenuModal').modal('show');
                $('#menuId').val(data.menu.id);
                $('#menuname').val(data.menu.menu);
                $('#ordermenu').val(data.menu.urutan);
            }
        })
    });
    $('.editSubMenu').on('click', function() {
        const subMenuId = $(this).data('id');
        $.ajax({
            url: "<?php echo base_url('menu/opensubmenuedit/') ?>" + subMenuId,
            type: "GET",
            data: {
                id: subMenuId
            },
            dataType: "json",
            success: function(data) {
                // $('#editMenuModal').modal('show');
                $('#subMenuId').val(data.submenu.id);
                $('#judul').val(data.submenu.title);
                $('#menu_select_id').val(data.submenu.menu_id);
                $('#sub_menu_url').val(data.submenu.url);
                $('#sub_menu_icon').val(data.submenu.icon);

                if (parseInt(data.submenu.is_active) === 1) {
                    $('#sub_menu_active').val(data.submenu.is_active);
                    $('#sub_menu_active').attr('checked', 'true');
                } else if (parseInt(data.submenu.is_active) === 0) {
                    $('#sub_menu_active').val(0);
                    $('#sub_menu_active').removeAttr('checked');
                }
            }
        });
        $('#sub_menu_active').on('change', function() {
            if ($(this).is(':checked')) {
                $(this).val(1);
            } else {
                $(this).val(0);
            }
        });
    });

    $('.editIncomeList').on('click', function() {
        const incomeListId = $(this).data('id');
        // console.log(incomeListId);
        $.ajax({
            url: "<?php echo base_url('financial/openincomelistedit/') ?>" + incomeListId,
            type: "GET",
            data: {
                id: incomeListId
            },
            dataType: "json",
            success: function(data) {
                // $('#editMenuModal').modal('show');
                $('#incomelistid').val(data.incomelist.id);
                $('#incomename').val(data.incomelist.income_name);
            }
        });
    })
    $('.editCategoryList').on('click', function() {
        const categoryListId = $(this).data('id');
        $.ajax({
            url: "<?php echo base_url('financial/opencategorylistedit/') ?>" + categoryListId,
            type: "GET",
            data: {
                id: categoryListId
            },
            dataType: "json",
            success: function(data) {
                // $('#editMenuModal').modal('show');
                $('#categorylist_id').val(data.categoryList.id);
                $('#categoryListName').val(data.categoryList.category_name);
            }
        });
    })
    $('.editWallet').on('click', function() {
        const walletId = $(this).data('id');
        // console.log(walletId)
        // $('#editMenuModal').modal('show');
        $.ajax({
            url: "<?php echo base_url('financial/openwalletedit/') ?>" + walletId,
            type: "GET",
            data: {
                id: walletId
            },
            dataType: "json",
            success: function(data) {
                $('#wallet_id').val(data.wallet.id);
                $('#wallet_name').val(data.wallet.wallet_name);
                $('#wallet_balance').val(data.wallet.total_balance);
            }
        });
    })
    $('.moveMoney').on('click', function() {
        const walletId = $(this).data('id');
        // $('#editMenuModal').modal('show');
        $.ajax({
            url: "<?php echo base_url('financial/openmovemoney/') ?>" + walletId,
            type: "GET",
            data: {
                id: walletId
            },
            dataType: "json",
            success: function(data) {
                // this wallet
                $('#money_id').val(data.money.id);
                $('#money_wallet_hidden').val(data.money.total_balance);
                $('#wallet_option').val(data.money.wallet_name);
                $('.this_wallet option').val(data.money.wallet_name).text(data.money.wallet_name);
                var total_balance = parseInt(data.money.total_balance);

                // Memformat angka ke dalam format Rupiah
                var formatRupiah = total_balance.toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                });
                $('.wallet_balance').html('<b>Your Balance</b> : ' + formatRupiah + '');

                var selectField = $('.wallet_destination');

                // Menghapus semua opsi sebelum menambahkan yang baru
                selectField.empty();

                $.each(data.walletdestitanion, function(index, wallet) {
                    var option = $('<option>').attr('value', wallet.wallet_name).text(wallet.wallet_name);
                    selectField.append(option);
                })
            }
        });
    })

    $('.editcashin').on('click', function() {
        const cashinId = $(this).data('id');
        // console.log(cashinId);
        $.ajax({
            url: "<?php echo base_url('financial/openeditcashin/') ?>" + cashinId,
            type: "GET",
            data: {
                id: cashinId
            },
            dataType: "json",
            success: function(data) {
                // console.log(data)
                // $('#editMenuModal').modal('show');
                $('#cashin_id').val(data.cashin.id);
                $('#date_new').val(data.cashin.date);
                $('#name_new').val(data.cashin.name);
                $('#amount_new').val(data.cashin.amount);
                $('#category_new').val(data.cashin.income_id);
                $('#walletNew').val(data.cashin.wallet_id);
                $('#last_amount').val(data.cashin.amount);
                $('#last_wallet').val(data.cashin.wallet_id);
            }
        });
    })
    $('.editcashout').on('click', function() {
        const cashoutId = $(this).data('id');
        // console.log(cashinId);
        $.ajax({
            url: "<?php echo base_url('financial/openeditcashout/') ?>" + cashoutId,
            type: "GET",
            data: {
                id: cashoutId
            },
            dataType: "json",
            success: function(data) {
                // console.log(data)
                // $('#editMenuModal').modal('show');
                $('#cashout_id').val(data.cashout.id);
                $('#date_new').val(data.cashout.date);
                $('#name_new').val(data.cashout.name);
                $('#amount_new').val(data.cashout.amount);
                $('#category_new').val(data.cashout.category_id);
                $('#walletNew').val(data.cashout.wallet_id);
                $('#last_amount').val(data.cashout.amount);
                $('#last_wallet').val(data.cashout.wallet_id);
            }
        });
    })

    /** chart pengeluaran pemasukan */
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    function number_format(number, decimals, dec_point, thousands_sep) {
        // *     example: number_format(1234.56, 2, ',', ' ');
        // *     return: '1 234,56'
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    // get chart awal
    $.ajax({
        url: "<?php echo base_url('financial/getDataSummary') ?>",
        type: "GET",
        dataType: "json",
        success: function(t) {
            var ctx = document.getElementById("myAreaChart");
            var myLineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [{
                        label: "Cash-in",
                        lineTension: 0.3,
                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                        borderColor: "rgba(78, 115, 223, 1)",
                        pointRadius: 3,
                        pointBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointBorderColor: "rgba(78, 115, 223, 1)",
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: t.monthlyTotal_cashin,
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
                            bottom: 0
                        }
                    },
                    scales: {
                        xAxes: [{
                            time: {
                                unit: 'date'
                            },
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 7
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                maxTicksLimit: 5,
                                padding: 10,
                                // Include a dollar sign in the ticks
                                callback: function(value, index, values) {
                                    return 'Rp. ' + number_format(value);
                                }
                            },
                            gridLines: {
                                color: "rgb(234, 236, 244)",
                                zeroLineColor: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            }
                        }],
                    },
                    legend: {
                        display: false
                    },
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        titleMarginBottom: 10,
                        titleFontColor: '#6e707e',
                        titleFontSize: 14,
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        intersect: false,
                        mode: 'index',
                        caretPadding: 10,
                        callbacks: {
                            label: function(tooltipItem, chart) {
                                var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
                            }
                        }
                    }
                }
            });
        }
    });

    function btnCashin() {
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        function number_format(number, decimals, dec_point, thousands_sep) {
            // *     example: number_format(1234.56, 2, ',', ' ');
            // *     return: '1 234,56'
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        // get chart awal
        $.ajax({
            url: "<?php echo base_url('financial/getDataSummary') ?>",
            type: "GET",
            dataType: "json",
            success: function(t) {
                var element = document.getElementById("myAreaChart");
                if (element) {
                    element.parentNode.removeChild(element);
                }
                var containerElements = document.getElementsByClassName("chart-area"); // Gantilah "container" dengan ID elemen yang sesuai
                for (var i = 0; i < containerElements.length; i++) {
                    var newCanvas = document.createElement("canvas");
                    newCanvas.id = "myAreaChart";
                    newCanvas.width = 664;
                    newCanvas.height = 320;
                    newCanvas.classList.add("chartjs-render-monitor");

                    // Anda dapat menambahkan atribut atau gaya tambahan jika diperlukan

                    // Menambahkan elemen canvas yang baru ke elemen saat ini dalam iterasi
                    containerElements[i].appendChild(newCanvas);
                }
                setTimeout(function() {
                    var ctx = document.getElementById("myAreaChart");
                    var myLineChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                            datasets: [{
                                label: "Cash-in",
                                lineTension: 0.3,
                                backgroundColor: "rgba(78, 115, 223, 0.05)",
                                borderColor: "rgba(78, 115, 223, 1)",
                                pointRadius: 3,
                                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                                pointBorderColor: "rgba(78, 115, 223, 1)",
                                pointHoverRadius: 3,
                                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                                pointHitRadius: 10,
                                pointBorderWidth: 2,
                                data: t.monthlyTotal_cashin,
                            }],
                        },
                        options: {
                            maintainAspectRatio: false,
                            layout: {
                                padding: {
                                    left: 10,
                                    right: 25,
                                    top: 25,
                                    bottom: 0
                                }
                            },
                            scales: {
                                xAxes: [{
                                    time: {
                                        unit: 'date'
                                    },
                                    gridLines: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    ticks: {
                                        maxTicksLimit: 7
                                    }
                                }],
                                yAxes: [{
                                    ticks: {
                                        maxTicksLimit: 5,
                                        padding: 10,
                                        // Include a dollar sign in the ticks
                                        callback: function(value, index, values) {
                                            return 'Rp. ' + number_format(value);
                                        }
                                    },
                                    gridLines: {
                                        color: "rgb(234, 236, 244)",
                                        zeroLineColor: "rgb(234, 236, 244)",
                                        drawBorder: false,
                                        borderDash: [2],
                                        zeroLineBorderDash: [2]
                                    }
                                }],
                            },
                            legend: {
                                display: false
                            },
                            tooltips: {
                                backgroundColor: "rgb(255,255,255)",
                                bodyFontColor: "#858796",
                                titleMarginBottom: 10,
                                titleFontColor: '#6e707e',
                                titleFontSize: 14,
                                borderColor: '#dddfeb',
                                borderWidth: 1,
                                xPadding: 15,
                                yPadding: 15,
                                displayColors: false,
                                intersect: false,
                                mode: 'index',
                                caretPadding: 10,
                                callbacks: {
                                    label: function(tooltipItem, chart) {
                                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                        return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
                                    }
                                }
                            }
                        }
                    });
                }, 100);
            }
        });
        document.querySelector('.btn_cashout').classList.remove('active');
        // Menonaktifkan tombol Cash-in
        document.querySelector('.btn_cashin').classList.add('active');
    }

    function btnCashout() {
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        function number_format(number, decimals, dec_point, thousands_sep) {
            // *     example: number_format(1234.56, 2, ',', ' ');
            // *     return: '1 234,56'
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        // get chart awal
        $.ajax({
            url: "<?php echo base_url('financial/getDataSummary') ?>",
            type: "GET",
            dataType: "json",
            success: function(t) {
                var element = document.getElementById("myAreaChart");
                if (element) {
                    element.parentNode.removeChild(element);
                }
                var containerElements = document.getElementsByClassName("chart-area"); // Gantilah "container" dengan ID elemen yang sesuai
                for (var i = 0; i < containerElements.length; i++) {
                    var newCanvas = document.createElement("canvas");
                    newCanvas.id = "myAreaChart";
                    newCanvas.width = 664;
                    newCanvas.height = 320;
                    newCanvas.classList.add("chartjs-render-monitor");

                    // Anda dapat menambahkan atribut atau gaya tambahan jika diperlukan

                    // Menambahkan elemen canvas yang baru ke elemen saat ini dalam iterasi
                    containerElements[i].appendChild(newCanvas);
                }

                setTimeout(function() {
                    var ctx = document.getElementById("myAreaChart");
                    var myLineChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                            datasets: [{
                                label: "Cash-out",
                                lineTension: 0.3,
                                backgroundColor: "rgba(78, 115, 223, 0.05)",
                                borderColor: "rgba(78, 115, 223, 1)",
                                pointRadius: 3,
                                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                                pointBorderColor: "rgba(78, 115, 223, 1)",
                                pointHoverRadius: 3,
                                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                                pointHitRadius: 10,
                                pointBorderWidth: 2,
                                data: t.monthlyTotal_cashout,
                            }],
                        },
                        options: {
                            maintainAspectRatio: false,
                            layout: {
                                padding: {
                                    left: 10,
                                    right: 25,
                                    top: 25,
                                    bottom: 0
                                }
                            },
                            scales: {
                                xAxes: [{
                                    time: {
                                        unit: 'date'
                                    },
                                    gridLines: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    ticks: {
                                        maxTicksLimit: 7
                                    }
                                }],
                                yAxes: [{
                                    ticks: {
                                        maxTicksLimit: 5,
                                        padding: 10,
                                        // Include a dollar sign in the ticks
                                        callback: function(value, index, values) {
                                            return 'Rp. ' + number_format(value);
                                        }
                                    },
                                    gridLines: {
                                        color: "rgb(234, 236, 244)",
                                        zeroLineColor: "rgb(234, 236, 244)",
                                        drawBorder: false,
                                        borderDash: [2],
                                        zeroLineBorderDash: [2]
                                    }
                                }],
                            },
                            legend: {
                                display: false
                            },
                            tooltips: {
                                backgroundColor: "rgb(255,255,255)",
                                bodyFontColor: "#858796",
                                titleMarginBottom: 10,
                                titleFontColor: '#6e707e',
                                titleFontSize: 14,
                                borderColor: '#dddfeb',
                                borderWidth: 1,
                                xPadding: 15,
                                yPadding: 15,
                                displayColors: false,
                                intersect: false,
                                mode: 'index',
                                caretPadding: 10,
                                callbacks: {
                                    label: function(tooltipItem, chart) {
                                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                        return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
                                    }
                                }
                            }
                        }
                    });
                }, 100);

            }
        });
        document.querySelector('.btn_cashout').classList.add('active');
        // Menonaktifkan tombol Cash-in
        document.querySelector('.btn_cashin').classList.remove('active');
    }
    /** Declare start-date */
    $(document).ready(function() {
        /** start of chart default */
        function defaultHtmlChartPie() {
            var label = $('#label-container').text('Cash-in Flow');
            var activeOneMonth = $('#btn_satu_bulan').addClass('active');
            var activeOneMonth = $('#btn_tiga_bulan').removeClass('active');
            var activeOneMonth = $('#btn_custom_bulan').removeClass('active');
            var valuePick = $("#value_pick").val();

            $.ajax({
                url: "<?php echo base_url('financial/filteredCategoryGraphic/') ?>",
                type: "GET",
                data: {
                    pickMonth: valuePick,
                },
                dataType: "json",
                success: function(response) {
                    var date = new Date(response.start_date);
                    var namaBulan = [
                        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                    ];

                    // Mendapatkan tanggal, bulan, dan tahun dari objek Date
                    var tanggal = date.getDate();
                    var bulan = date.getMonth();
                    var tahun = date.getFullYear();

                    // Membuat teks dalam format 'dd Bulan yyyy'
                    var teksTanggal = tanggal + ' ' + namaBulan[bulan] + ' ' + tahun;
                    var pieArray = [];
                    pieArray.push(response.total_cashout);
                    pieArray.push(response.selisih_cashin);

                    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
                    Chart.defaults.global.defaultFontColor = '#858796';

                    var pieChart = document.getElementById("myPieChart");
                    var myPieChart = new Chart(pieChart, {
                        type: 'doughnut',
                        data: {
                            labels: ["Pengeluaran", "Sisa Uang"],
                            datasets: [{
                                data: pieArray,
                                backgroundColor: ['#ED4245', '#1cc88a'],
                                hoverBackgroundColor: ['#ED426D', '#17a673'],
                                hoverBorderColor: "rgba(234, 236, 244, 1)",
                            }],
                        },
                        options: {
                            maintainAspectRatio: false,
                            tooltips: {
                                backgroundColor: "rgb(255,255,255)",
                                bodyFontColor: "#858796",
                                borderColor: '#dddfeb',
                                borderWidth: 1,
                                xPadding: 15,
                                yPadding: 15,
                                displayColors: false,
                                caretPadding: 10,
                            },
                            legend: {
                                display: false
                            },
                            cutoutPercentage: 80,
                        },
                    });

                    var pieCaption = document.getElementById('caption_pie');
                    pieCaption.innerHTML = 'Kondisi keuanganmu sejak ' + '<b>' + teksTanggal + '</b>' + ' sampai dengan hari ini. ' + '<b>' + response.message + '</b>';
                }
            });

            $('#btn_satu_bulan').on('click', function() {
                $("#btn_satu_bulan").addClass("active");
                $("#btn_tiga_bulan").removeClass("active");
                $("#btn_custom_bulan").removeClass("active");
                $("#value_pick").val("1 Month");

                var valuePick = $("#value_pick").val();

                $.ajax({
                    url: "<?php echo base_url('financial/filteredCategoryGraphic/') ?>",
                    type: "GET",
                    data: {
                        pickMonth: valuePick,
                    },
                    dataType: "json",
                    success: function(response) {
                        var date = new Date(response.start_date);
                        var namaBulan = [
                            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                        ];

                        // Mendapatkan tanggal, bulan, dan tahun dari objek Date
                        var tanggal = date.getDate();
                        var bulan = date.getMonth();
                        var tahun = date.getFullYear();

                        // Membuat teks dalam format 'dd Bulan yyyy'
                        var teksTanggal = tanggal + ' ' + namaBulan[bulan] + ' ' + tahun;
                        var pieArray = [];
                        pieArray.push(response.total_cashout);
                        pieArray.push(response.selisih_cashin);

                        var element = document.getElementById("myPieChart");
                        if (element) {
                            element.parentNode.removeChild(element);
                        }
                        var containerElements = document.getElementsByClassName("chart-pie"); // Gantilah "container" dengan ID elemen yang sesuai
                        for (var i = 0; i < containerElements.length; i++) {
                            var newCanvas = document.createElement("canvas");
                            newCanvas.id = "myPieChart";
                            newCanvas.width = 299;
                            newCanvas.height = 253;
                            newCanvas.classList.add("chartjs-render-monitor");

                            // Anda dapat menambahkan atribut atau gaya tambahan jika diperlukan

                            // Menambahkan elemen canvas yang baru ke elemen saat ini dalam iterasi
                            containerElements[i].appendChild(newCanvas);
                        }

                        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
                        Chart.defaults.global.defaultFontColor = '#858796';

                        var pieChart = document.getElementById("myPieChart");
                        var myPieChart = new Chart(pieChart, {
                            type: 'doughnut',
                            data: {
                                labels: ["Pengeluaran", "Sisa Uang"],
                                datasets: [{
                                    data: pieArray,
                                    backgroundColor: ['#ED4245', '#1cc88a'],
                                    hoverBackgroundColor: ['#ED426D', '#17a673'],
                                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                                }],
                            },
                            options: {
                                maintainAspectRatio: false,
                                tooltips: {
                                    backgroundColor: "rgb(255,255,255)",
                                    bodyFontColor: "#858796",
                                    borderColor: '#dddfeb',
                                    borderWidth: 1,
                                    xPadding: 15,
                                    yPadding: 15,
                                    displayColors: false,
                                    caretPadding: 10,
                                },
                                legend: {
                                    display: false
                                },
                                cutoutPercentage: 80,
                            },
                        });

                        var pieCaption = document.getElementById('caption_pie');
                        pieCaption.innerHTML = 'Kondisi keuanganmu sejak ' + '<b>' + teksTanggal + '</b>' + ' sampai dengan hari ini. ' + '<b>' + response.message + '</b>';
                    }
                });

            })
            $('#btn_tiga_bulan').on('click', function() {
                $("#btn_satu_bulan").removeClass("active");
                $("#btn_tiga_bulan").addClass("active");
                $("#btn_custom_bulan").removeClass("active");
                $("#value_pick").val("3 Month");

                var valuePick = $("#value_pick").val();

                $.ajax({
                    url: "<?php echo base_url('financial/filteredCategoryGraphic/') ?>",
                    type: "GET",
                    data: {
                        pickMonth: valuePick,
                    },
                    dataType: "json",
                    success: function(response) {
                        var date = new Date(response.start_date);
                        var namaBulan = [
                            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                        ];

                        // Mendapatkan tanggal, bulan, dan tahun dari objek Date
                        var tanggal = date.getDate();
                        var bulan = date.getMonth();
                        var tahun = date.getFullYear();

                        // Membuat teks dalam format 'dd Bulan yyyy'
                        var teksTanggal = tanggal + ' ' + namaBulan[bulan] + ' ' + tahun;
                        var pieArray = [];
                        pieArray.push(response.total_cashout);
                        pieArray.push(response.selisih_cashin);

                        var element = document.getElementById("myPieChart");
                        if (element) {
                            element.parentNode.removeChild(element);
                        }
                        var containerElements = document.getElementsByClassName("chart-pie"); // Gantilah "container" dengan ID elemen yang sesuai
                        for (var i = 0; i < containerElements.length; i++) {
                            var newCanvas = document.createElement("canvas");
                            newCanvas.id = "myPieChart";
                            newCanvas.width = 299;
                            newCanvas.height = 253;
                            newCanvas.classList.add("chartjs-render-monitor");

                            // Anda dapat menambahkan atribut atau gaya tambahan jika diperlukan

                            // Menambahkan elemen canvas yang baru ke elemen saat ini dalam iterasi
                            containerElements[i].appendChild(newCanvas);
                        }

                        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
                        Chart.defaults.global.defaultFontColor = '#858796';

                        var pieChart = document.getElementById("myPieChart");
                        var myPieChart = new Chart(pieChart, {
                            type: 'doughnut',
                            data: {
                                labels: ["Pengeluaran", "Sisa Uang"],
                                datasets: [{
                                    data: pieArray,
                                    backgroundColor: ['#ED4245', '#1cc88a'],
                                    hoverBackgroundColor: ['#ED426D', '#17a673'],
                                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                                }],
                            },
                            options: {
                                maintainAspectRatio: false,
                                tooltips: {
                                    backgroundColor: "rgb(255,255,255)",
                                    bodyFontColor: "#858796",
                                    borderColor: '#dddfeb',
                                    borderWidth: 1,
                                    xPadding: 15,
                                    yPadding: 15,
                                    displayColors: false,
                                    caretPadding: 10,
                                },
                                legend: {
                                    display: false
                                },
                                cutoutPercentage: 80,
                            },
                        });

                        var pieCaption = document.getElementById('caption_pie');
                        pieCaption.innerHTML = 'Kondisi keuanganmu sejak ' + '<b>' + teksTanggal + '</b>' + ' sampai dengan hari ini. ' + '<b>' + response.message + '</b>';
                    }
                });
            })

            $('#btn_simpan_pick').on('click', function() {
                $("#btn_satu_bulan").removeClass("active");
                $("#btn_tiga_bulan").removeClass("active");
                $("#btn_custom_bulan").addClass("active");
                $("#value_pick").val("custom_date");

                var valuePick = $("#value_pick").val();
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();

                if (startDate === '' || endDate === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please input Field!',
                    })
                    return false;
                }

                $.ajax({
                    url: "<?php echo base_url('financial/filteredCategoryGraphic/') ?>",
                    type: "GET",
                    data: {
                        pickMonth: valuePick,
                        startDate: startDate,
                        endDate: endDate,
                    },
                    dataType: "json",
                    success: function(response) {
                        var date = new Date(response.start_date);
                        var namaBulan = [
                            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                        ];

                        // Mendapatkan tanggal, bulan, dan tahun dari objek Date
                        var tanggal = date.getDate();
                        var bulan = date.getMonth();
                        var tahun = date.getFullYear();

                        // Membuat teks dalam format 'dd Bulan yyyy'
                        var teksTanggal = tanggal + ' ' + namaBulan[bulan] + ' ' + tahun;
                        var pieArray = [];
                        pieArray.push(response.total_cashout);
                        pieArray.push(response.selisih_cashin);

                        var element = document.getElementById("myPieChart");
                        if (element) {
                            element.parentNode.removeChild(element);
                        }
                        var containerElements = document.getElementsByClassName("chart-pie"); // Gantilah "container" dengan ID elemen yang sesuai
                        for (var i = 0; i < containerElements.length; i++) {
                            var newCanvas = document.createElement("canvas");
                            newCanvas.id = "myPieChart";
                            newCanvas.width = 299;
                            newCanvas.height = 253;
                            newCanvas.classList.add("chartjs-render-monitor");

                            // Anda dapat menambahkan atribut atau gaya tambahan jika diperlukan

                            // Menambahkan elemen canvas yang baru ke elemen saat ini dalam iterasi
                            containerElements[i].appendChild(newCanvas);
                        }

                        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
                        Chart.defaults.global.defaultFontColor = '#858796';

                        var pieChart = document.getElementById("myPieChart");
                        var myPieChart = new Chart(pieChart, {
                            type: 'doughnut',
                            data: {
                                labels: ["Pengeluaran", "Sisa Uang"],
                                datasets: [{
                                    data: pieArray,
                                    backgroundColor: ['#ED4245', '#1cc88a'],
                                    hoverBackgroundColor: ['#ED426D', '#17a673'],
                                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                                }],
                            },
                            options: {
                                maintainAspectRatio: false,
                                tooltips: {
                                    backgroundColor: "rgb(255,255,255)",
                                    bodyFontColor: "#858796",
                                    borderColor: '#dddfeb',
                                    borderWidth: 1,
                                    xPadding: 15,
                                    yPadding: 15,
                                    displayColors: false,
                                    caretPadding: 10,
                                },
                                legend: {
                                    display: false
                                },
                                cutoutPercentage: 80,
                            },
                        });

                        var pieCaption = document.getElementById('caption_pie');
                        pieCaption.innerHTML = 'Kondisi keuanganmu sejak ' + '<b>' + teksTanggal + '</b>' + ' sampai dengan hari ini. ' + '<b>' + response.message + '</b>';
                    }
                });
            })
        }
        defaultHtmlChartPie();
        /** end of chart default */

        function htmlChartCashin() {
            var label = $('#label-container').text('Cash-in Flow');
            var activeOneMonth = $('#btn_satu_bulan').addClass('active');
            var activeOneMonth = $('#btn_tiga_bulan').removeClass('active');
            var activeOneMonth = $('#btn_custom_bulan').removeClass('active');
            var valuePick = $("#value_pick").val();

            $.ajax({
                url: "<?php echo base_url('financial/filteredCashinGraphic/') ?>",
                type: "GET",
                data: {
                    pickMonth: valuePick
                },
                dataType: "json",
                success: function(response) {

                    console.log(response)
                }
            })

        }

        $('#btn_health').on('click', function() {
            defaultHtmlChartPie()
        })

        $('#btn_cashinflow').on('click', function() {
            htmlChartCashin()
        })
    });
</script>
</body>

</html>