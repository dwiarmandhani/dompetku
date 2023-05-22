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
</script>
</body>

</html>