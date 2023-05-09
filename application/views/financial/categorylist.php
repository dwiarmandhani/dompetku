<!-- $categorylist -->

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?php echo $title; ?></h1>
    <div class="row">
        <div class="col-lg-6">
            <?= form_error('categoryList', '<div class="alert alert-danger" role="alert">', '</div>'); ?>
            <?= $this->session->flashdata('message'); ?>

            <a href="" class="btn btn-primary  mb-3" data-toggle="modal" data-target="#newCategoryListModal">Add New Menu</a>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Category Name</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($categorylist) { ?>
                        <?php $i = 1; ?>
                        <?php foreach ($categorylist as $list) : ?>
                            <tr>
                                <th scope="row"><?= $i; ?></th>
                                <td><?= $list['category_name']; ?></td>
                                <td>
                                    <a href="" class="badge badge-success editCategoryList" data-toggle="modal" data-target="#editCategoryListModal" data-id="<?php echo $list['id']; ?>">edit</a>
                                    <a href="<?php echo base_url('financial/deletecategorylist'); ?>/<?= $list['id']; ?>" onclick="return confirm('Are you sure you want to delete this item?');" class="badge badge-danger">delete</a>
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
<div class="modal fade" id="newCategoryListModal" tabindex="-1" aria-labelledby="newCategoryListModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newCategoryListModalLabel">Add New Category List</h5>

            </div>
            <form action="<?= base_url('financial/categorylist'); ?>" method="post">
                <div class="modal-body">

                    <div class="form-group">
                        <input type="text" class="form-control" id="categoryList" name="categoryList" placeholder="Category Name...">
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
<div class="modal fade" id="editCategoryListModal" tabindex="-1" aria-labelledby="editCategoryListModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryListModalLabel">Edit Menu</h5>

            </div>
            <form action="<?= base_url('financial/editcategorylist'); ?>" method="post">
                <div class="modal-body">

                    <input type="hidden" class="form-control" id="categorylist_id" name="categorylist_id" placeholder="Category List Name...">
                    <div class="form-group">
                        <input type="text" class="form-control" id="categoryListName" name="categoryListName" placeholder="Category List Name...">
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