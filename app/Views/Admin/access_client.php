<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">Client Access for <u><?= $username ?></u></h3>

            <form id="clientForm">
                <input type="hidden" name="user_id" value="<?= $userId ?>">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Client Name</th>
                            <th width="120">Access</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($clients as $c): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $c->client_name ?></td>
                                <td class="text-center">
                                    <input type="checkbox"
                                        name="client_id[]"
                                        value="<?= $c->client_id ?>"
                                        <?= ($c->has_access ? 'checked' : '') ?>>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <button type="button" id="btnSave" class="btn btn-primary mt-3">
                    <i class="fa fa-save"></i> Save Access
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    $("#btnSave").on("click", function() {

        Swal.fire({
            title: "Simpan Client Access?",
            text: "Akses client akan diperbarui!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, simpan!"
        }).then((res) => {

            if (res.isConfirmed) {
                let formData = $("#clientForm").serialize();

                $.ajax({
                url: "<?= base_url('admin/Access_client/save_access_client') ?>",
                    type: "POST",
                    data: formData,
                    dataType: "json",

                    success: function(resp) {
                        if (resp.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: resp.message,
                                timer: 1200,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "<?= base_url('admin/Access_client') ?>";
                            });
                        } else {
                            Swal.fire("Gagal", resp.message, "error");
                        }
                    },

                    error: function() {
                        Swal.fire("Error", "Server error!", "error");
                    }
                });
            }

        });
    });
</script>


<script src="<?php echo base_url(); ?>/assets/js/main.js"></script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>