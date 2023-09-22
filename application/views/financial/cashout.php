<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?php echo $title; ?></h1>
    <div class="row">
        <div class="col-lg">
            <?= form_error('date', '<div class="alert alert-danger" role="alert">', '</div>'); ?>
            <?= form_error('name', '<div class="alert alert-danger" role="alert">', '</div>'); ?>
            <?= form_error('category', '<div class="alert alert-danger" role="alert">', '</div>'); ?>
            <?= form_error('wallet', '<div class="alert alert-danger" role="alert">', '</div>'); ?>
            <?= form_error('amount', '<div class="alert alert-danger" role="alert">', '</div>'); ?>
            <?= $this->session->flashdata('message'); ?>

            <a href="" class="btn btn-primary  mb-3" data-toggle="modal" data-target="#newcashoutModal">Add New Outcome</a>
            <?php
            function formatRupiah($angka)
            {

                $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
                return $hasil_rupiah;
            }
            if (isset($cashout)) { ?>

                <div class="row">
                    <div class="card border-left-primary shadow h-100 py-2 ml-2 mb-3">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Cash-out</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo formatRupiah($total_cashout);
                                                                                        ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-money-bill-wave-alt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            } ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Date</th>
                        <th scope="col">Name</th>
                        <th scope="col">Category</th>
                        <th scope="col">Wallet</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($cashout) {
                    ?>
                        <?php $i = 1; ?>
                        <?php foreach ($cashout as $list) : ?>
                            <tr>
                                <th scope="row"><?= $i; ?></th>
                                <td><?= $list['date']; ?></td>
                                <td><?= $list['name']; ?></td>
                                <td><?= $list['category_name']; ?></td>
                                <td><?= $list['wallet_name']; ?></td>
                                <td><?= formatRupiah($list['amount']); ?></td>
                                <td>
                                    <a href="" class="badge badge-success editcashout" data-toggle="modal" data-target="#editcashoutModal" data-id="<?php echo $list['id']; ?>">edit</a>
                                    <a href="<?php echo base_url('financial/deletecashout'); ?>/<?= $list['id']; ?>" onclick="return confirm('Are you sure you want to delete this item?');" class="badge badge-danger">delete</a>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    <?php } else {  ?>
                        <tr>
                            <h1>Data not found</h1>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>



</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Modal -->
<div class="modal fade" id="newcashoutModal" tabindex="-1" aria-labelledby="newcashoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newcashoutModalLabel">Add New Outcome List</h5>

            </div>
            <form action="<?= base_url('financial/cashout'); ?>" method="post">
                <div class="modal-body">

                    <div class="form-group">
                        <input type="date" class="form-control" id="date" name="date">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name...">
                    </div>

                    <div class="form-group">
                        <select name="category" id="category" class="form-control">
                            <option value="">Select Category</option>
                            <?php foreach ($category as $cat) : ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['category_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="wallet" id="wallet" class="form-control">
                            <option value="">Select Wallet</option>
                            <?php foreach ($walletName as $wallet) : ?>
                                <option value="<?php echo $wallet['id']; ?>"><?php echo $wallet['wallet_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="number" class="form-control" id="amount" name="amount" placeholder="Amount...">
                    </div>

                </div>
                <div class=" modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- Modal Edit -->
<div class="modal fade" id="editcashoutModal" tabindex="-1" aria-labelledby="editcashoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editcashoutModalLabel">Edit Outcome</h5>

            </div>
            <form action="<?= base_url('financial/editcashout'); ?>" method="post">
                <div class="modal-body">

                    <input type="hidden" class="form-control" id="cashout_id" name="cashout_id">
                    <input type="hidden" class="form-control" id="last_amount" name="last_amount">
                    <input type="hidden" class="form-control" id="last_wallet" name="last_wallet">
                    <div class="form-group">
                        <input type="date" class="form-control" id="date_new" name="date_new">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="name_new" name="name_new" placeholder="Name...">
                    </div>

                    <div class="form-group">
                        <select name="category_new" id="category_new" class="form-control">
                            <option value="">Select Category</option>
                            <?php foreach ($category as $cat) : ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['category_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">

                        <select name="walletNew" id="walletNew" class="form-control">
                            <option value="">Select Wallet</option>
                            <?php foreach ($walletName as $wallet) : ?>
                                <option value="<?php echo $wallet['id']; ?>"><?php echo $wallet['wallet_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="number" class="form-control" id="amount_new" name="amount_new" placeholder="Amount...">
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