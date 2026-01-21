<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">User List</h3>

            <div class="tile-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= esc($u['user_id']); ?></td>
                                <td><?= esc($u['full_name'] ?? '-'); ?></td>
                                <td><?= esc($u['user_name'] ?? '-'); ?></td>
                                <td>
                                    <a href="<?= base_url('admin/Access_menu/detail/' . $u['user_id']); ?>"
                                        class="btn btn-primary btn-sm">
                                        <i class="fa fa-lock"></i> Set Access
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>/assets/js/main.js"></script>