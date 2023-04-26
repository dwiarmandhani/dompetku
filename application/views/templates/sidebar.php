<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-danger sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url(); ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-wallet"></i>
        </div>
        <div class="sidebar-brand-text mx-3">DOMPETKU</div>
    </a>

    <?php
    $role_id = $this->session->userdata('role_id');
    $queryMenu = "SELECT `user_menu`.`id`, `menu` FROM `user_menu` JOIN `user_access_menu` ON `user_menu`.`id` = `user_access_menu`.`menu_id` WHERE `user_access_menu`.`role_id` = $role_id ORDER BY `user_menu`.`urutan` ASC";

    $menu = $this->db->query($queryMenu)->result_array();
    ?>

    <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- Heading -->

    <?php foreach ($menu as $m) : ?>
        <div class="sidebar-heading">
            <?= $m['menu']; ?>
        </div>


        <?php
        $menu_id = $m['id'];
        $querySubMenu = "SELECT * FROM `user_sub_menu` JOIN `user_menu` ON `user_sub_menu`.`menu_id` = `user_menu`.`id` WHERE `user_sub_menu`.`menu_id` = $menu_id AND `user_sub_menu`.`is_active` = 1";
        $subMenu = $this->db->query($querySubMenu)->result_array();
        ?>

        <?php foreach ($subMenu as $sm) : ?>
            <?php if ($title == $sm['title']) { ?>
                <li class="nav-item active">
                <?php } else { ?>
                <li class="nav-item">
                <?php } ?>
                <a class="nav-link pb-0" href="<?php echo base_url($sm['url']); ?>">
                    <i class="<?php echo $sm['icon']; ?>"></i>
                    <span><?php echo $sm['title']; ?></span></a>
                </li>
            <?php endforeach; ?>
            <hr class="sidebar-divider mt-3">
        <?php endforeach; ?>

        <!-- Divider -->
        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('auth/logout'); ?>">
                <i class="fas fa-fw fa-sign-out-alt"></i>
                <span>Logout</span></a>
        </li>

        <hr class="sidebar-divider">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

</ul>
<!-- End of Sidebar -->