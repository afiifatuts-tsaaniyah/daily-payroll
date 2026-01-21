<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">Access Menu <u><?= $username ?></u></h3>

            <div class="tile-body m-2">

                <form id="accessForm">
                    <input type="hidden" name="user_id" value="<?= $userId ?>">

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered w-100" id="menuTable">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th>Menu Name</th>
                                    <th>Group Name</th>
                                    <th>Access</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($menus as $m) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= esc($m->menu_name); ?></td>
                                        <td><?= esc($m->menu_group); ?></td>
                                        <td class="text-center">
                                            <input type="checkbox"
                                                name="menu_id[]"
                                                value="<?= $m->menu_id ?>"
                                                <?= ($m->has_access ? 'checked' : '') ?>>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <button type="button" id="btnSave" class="btn btn-primary">
                            <i class="fa fa-save"></i> Save Access
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $("#btnSave").on("click", function(e) {

        Swal.fire({
            title: "Simpan Akses?",
            text: "Perubahan akses menu akan disimpan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, simpan!"
        }).then((result) => {

            if (result.isConfirmed) {

                let formData = $("#accessForm").serialize(); // ambil data form

                $.ajax({
                    url: "<?= base_url('admin/Access_menu/save_access_menu') ?>",
                    type: "POST",
                    data: formData,
                    dataType: "json",

                    success: function(res) {
                        if (res.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message,
                                timer: 1200,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "<?= base_url('admin/Access_menu') ?>";
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: res.message
                            });
                        }
                    },

                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan pada server!'
                        });
                    }

                });

            }
        });
    });
</script>


<script src="<?php echo base_url(); ?>/assets/js/main.js"></script>