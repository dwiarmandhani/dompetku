<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?php echo $title; ?></h1>
    <div class="row">
        <div class="col-lg-6">
            <?= form_error('menu', '<div class="alert alert-danger" role="alert">', '</div>'); ?>
            <?= $this->session->flashdata('message'); ?>

            <a href="" class="btn btn-primary  mb-3" data-toggle="modal" data-target="#newMenuModal">Add New Menu</a>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Menu</th>
                        <th scope="col">Order List</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($menu as $m) : ?>
                        <tr>
                            <th scope="row"><?= $i; ?></th>
                            <td><?= $m['menu']; ?></td>
                            <td><?= $m['urutan']; ?></td>
                            <td>
                                <a href="" class="badge badge-success editMenu" data-toggle="modal" data-target="#editMenuModal" data-id="<?php echo $m['id']; ?>">edit</a>
                                <a href="<?php echo base_url('menu/deletemenu'); ?>/<?= $m['id']; ?>" onclick="return confirm('Are you sure you want to delete this item?');" class="badge badge-danger">delete</a>
                            </td>
                        </tr>
                        <?php $i++; ?>
                    <?php endforeach; ?>
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
<div class="modal fade" id="newMenuModal" tabindex="-1" aria-labelledby="newMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newMenuModalLabel">Add New Menu</h5>

            </div>
            <form action="<?= base_url('menu'); ?>" method="post">
                <div class="modal-body">

                    <div class="form-group">
                        <input type="text" class="form-control" id="menu" name="menu" placeholder="Menu Name...">
                    </div>
                    <div class="form-group">
                        <input type="number" class="form-control" id="order" name="order" placeholder="Order...">
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
<div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMenuModalLabel">Edit Menu</h5>

            </div>
            <form action="<?= base_url('menu/editmenu'); ?>" method="post">
                <div class="modal-body">

                    <input type="hidden" class="form-control" id="menuId" name="menu_id" placeholder="Menu Name...">
                    <div class="form-group">
                        <input type="text" class="form-control" id="menuname" name="menu" placeholder="Menu Name...">
                    </div>
                    <div class="form-group">
                        <input type="number" class="form-control" id="ordermenu" name="order" placeholder="Order...">
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