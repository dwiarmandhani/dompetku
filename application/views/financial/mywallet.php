<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?php echo $title; ?></h1>
    <div class="row">
        <div class="col">
            <?= form_error('walletName', '<div class="alert alert-danger" role="alert">', '</div>'); ?>
            <?= $this->session->flashdata('message'); ?>

            <a href="" class="btn btn-primary btn-sm mb-3" data-toggle="modal" data-target="#newWalletModal">Add New Wallet</a>
            <div class="row">
                <?php
                function rupiah($angka)
                {

                    $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
                    return $hasil_rupiah;
                }

                if ($myWallet) {
                    foreach ($myWallet as $grid) {
                ?>
                        <div class="col-sm-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title"><b><?php echo $grid['wallet_name']; ?></b></h4>
                                    <p class="card-text">Saldo : <b><?php echo rupiah($grid['total_balance']); ?></b><br>
                                        <?php if ((int)$grid['total_balance'] === 0) { ?>
                                            <small class="card-text text-danger">Top-Up this Wallet at the <b>Cash-In</b> Feature!</small>
                                        <?php }  ?>

                                    </p>
                                    <a href="" class="btn btn-secondary btn-sm mt-3 moveMoney" data-toggle="modal" data-target="#moveMoneyModal" data-id="<?php echo $grid['id']; ?>">move money</a>
                                    <a href="" class="btn btn-success btn-sm mt-3 editWallet" data-toggle="modal" data-target="#editWalletModal" data-id="<?php echo $grid['id']; ?>">edit</a>
                                    <a href="<?php echo base_url('financial/detelewallet'); ?>/<?= $grid['id']; ?>" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger btn-sm mt-3">delete</a>
                                </div>
                            </div>
                        </div>
                    <?php }
                } else { ?>
                    <div> Data Not Found</div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</div>
<!-- End of Main Content -->

<!-- Modal -->
<div class="modal fade" id="newWalletModal" tabindex="-1" aria-labelledby="newWalletModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newWalletModalLabel">Add New Wallet List</h5>

            </div>
            <form action="<?= base_url('financial/mywallet'); ?>" method="post">
                <div class="modal-body">

                    <div class="form-group">
                        <input type="text" class="form-control" id="walletName" name="walletName" placeholder="Wallet Name...">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="balance" name="balance" value="0" disabled>
                        <small class="text-danger ml-1">Top-Up this Wallet at the Cash In input!</small>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- Modal Edit -->
<div class="modal fade" id="editWalletModal" tabindex="-1" aria-labelledby="editWalletModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editWalletModalModalLabel">Edit Wallet</h5>

            </div>
            <form action="<?= base_url('financial/editwallet'); ?>" method="post">
                <div class="modal-body">

                    <input type="hidden" class="form-control" id="wallet_id" name="wallet_id" placeholder="Wallet List Name...">
                    <div class="form-group">
                        <input type="text" class="form-control" id="wallet_name" name="wallet_name" placeholder="Wallet List Name...">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" id="wallet_balance" name="balance" value="" disabled>
                        <small class="text-danger ml-1">Top-Up this Wallet at the Cash In input!</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- Modal Move money -->
<div class="modal fade" id="moveMoneyModal" tabindex="-1" aria-labelledby="moveMoneyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="moveMoneyModalModalLabel">Move Money</h5>

            </div>
            <form action="<?= base_url('financial/movemoney'); ?>" method="post">
                <div class="modal-body">

                    <input type="hidden" class="form-control" id="money_id" name="money_id">
                    <input type="hidden" class="form-control" id="money_wallet_hidden" name="money_wallet_hidden">
                    <input type="hidden" class="form-control" id="wallet_option" name="wallet_option">
                    <div class="form-group">
                        <select class="custom-select this_wallet" disabled>
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <h5 class="wallet_balance ml-1"></h5>
                    </div>
                    <div class="form-group walletdest">
                        <select class="custom-select wallet_destination" name="select_destination">
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="money_amount" name="money_amount" placeholder="Your Amount...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>
            </form>

        </div>
    </div>
</div>