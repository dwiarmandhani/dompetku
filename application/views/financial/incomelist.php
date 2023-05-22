<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?php echo $title; ?></h1>
    <div class="row">
        <div class="col-lg-6">
            <?= form_error('incomeList', '<div class="alert alert-danger" role="alert">', '</div>'); ?>
            <?= $this->session->flashdata('message'); ?>

            <a href="" class="btn btn-primary  mb-3" data-toggle="modal" data-target="#newIncomeListModal">Add New Income</a>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Income Name</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($incomelist) { ?>
                        <?php $i = 1; ?>
                        <?php foreach ($incomelist as $list) : ?>
                            <tr>
                                <th scope="row"><?= $i; ?></th>
                                <td><?= $list['income_name']; ?></td>
                                <td>
                                    <a href="" class="badge badge-success editIncomeList" data-toggle="modal" data-target="#editIncomeListModal" data-id="<?php echo $list['id']; ?>">edit</a>
                                    <a href="<?php echo base_url('financial/deleteincomelist'); ?>/<?= $list['id']; ?>" onclick="return confirm('Are you sure you want to delete this item?');" class="badge badge-danger">delete</a>
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

<!-- modal -->

<!-- Modal -->
<div class="modal fade" id="newIncomeListModal" tabindex="-1" aria-labelledby="newIncomeListModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newIncomeListModalLabel">Add New Income List</h5>

            </div>
            <form action="<?= base_url('financial/incomelist'); ?>" method="post">
                <div class="modal-body">

                    <div class="form-group">
                        <input type="text" class="form-control" id="incomeList" name="incomeList" placeholder="Income Name...">
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
<div class="modal fade" id="editIncomeListModal" tabindex="-1" aria-labelledby="editIncomeListModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editIncomeListModalLabel">Edit Income</h5>

            </div>
            <form action="<?= base_url('financial/editincomelist'); ?>" method="post">
                <div class="modal-body">

                    <input type="hidden" class="form-control" id="incomelistid" name="incomelist_id" placeholder="Income List Name...">
                    <div class="form-group">
                        <input type="text" class="form-control" id="incomename" name="incomename" placeholder="Income List Name...">
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