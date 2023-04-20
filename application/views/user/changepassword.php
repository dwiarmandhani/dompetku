<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?php echo $title; ?></h1>
    <div class="row">
        <div class="col-lg-6">
            <?php echo $this->session->flashdata('message'); ?>

            <form action="<?php echo base_url('user/changepassword') ?>" method="post">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" name="current_password" class="form-control" id="current_password">
                    <?= form_error('current_password', '<small class="text-danger pl-3">', '</small>'); ?>
                </div>
                <div class="form-group">
                    <label for="newPassword1">New password</label>
                    <input type="password" class="form-control" id="newPassword1" name="newPassword1">
                    <?= form_error('newPassword1', '<small class="text-danger pl-3">', '</small>'); ?>

                </div>
                <div class="form-group">
                    <label for="newPassword2">New password</label>
                    <input type="password" class="form-control" id="newPassword2" name="newPassword2">
                    <?= form_error('newPassword2', '<small class="text-danger pl-3">', '</small>'); ?>


                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Change password</button>
                </div>
            </form>
        </div>
    </div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->