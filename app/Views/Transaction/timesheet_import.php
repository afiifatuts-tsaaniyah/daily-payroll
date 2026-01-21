      <Style>
          #spinner {
              position: fixed;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
              background-color: rgba(0, 0, 0, 0.5);
              display: flex;
              justify-content: center;
              align-items: center;
              z-index: 9999;
          }

          .loading {
              border: 6px solid #f3f3f3;
              border-radius: 50%;
              border-top: 6px solid #3498db;
              width: 40px;
              height: 40px;
              animation: spin 1s linear infinite;
          }

          @keyframes spin {
              0% {
                  transform: rotate(0deg);
              }

              100% {
                  transform: rotate(360deg);
              }
          }
      </Style>

      <div id="spinner" style="display:none;">
          <div class="loading"></div>
      </div>

      <div class="row">
          <div class="col-md-12">
              <div class="tile">
                  <h3 class="tile-title">Download Process</h3>
                  <div class="tile-footer">
                  </div>
                  <div class="tile-body">
                      <div class="container pt-3">
                          <h3 class="tile-title">Timesheet</h3>

                          <div>
                              <form method="post" id="rosterUploadForm" enctype="multipart/form-data" autocomplete="off">
                                  <br>
                                  <div class="row">
                                      <div class="col-xs-12">
                                          <div class="col-md-12 ">
                                              <input type="file" class="form-control" name="file" />
                                          </div>
                                          <br>
                                          <button class="btn btn-primary mb-3 mr-2" id="uploadRoster" type="button">
                                              <i class="fa fa-upload me-1"></i> Timesheet Upload
                                          </button>
                                          <button type="button" class="btn btn-success mb-3" data-toggle="modal"
                                              data-target="#modalTimesheetTemplate">
                                              <i class="fa fa-download me-1"></i> Timesheet Template
                                          </button>
                                      </div>
                                  </div>
                              </form>
                          </div>
                      </div>
                      <hr />

                      <div class="container pt-3">
                          <h3 class="tile-title">Allowance</h3>

                          <div>
                              <form method="post" id="allowanceUploadForm" enctype="multipart/form-data" autocomplete="off">
                                  <br>
                                  <div class="row">
                                      <div class="col-xs-12">
                                          <div class="col-md-12">
                                              <input type="file" class="form-control" name="file" />
                                          </div>
                                          <br>
                                          <button class="btn btn-primary mb-3 mr-2" id="uploadAllowance" type="button">
                                              <i class="fa fa-upload me-1"></i> Allowance Upload
                                          </button>
                                          <button type="button" class="btn btn-success mb-3" data-toggle="modal"
                                              data-target="#modalAllowanceTemplate">
                                              <i class="fa fa-download me-1"></i> Allowance Template
                                          </button>
                                      </div>
                                  </div>
                              </form>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>


      <!-- ===================== MODALS ===================== -->

      <div class="modal modal-blur fade" id="modalTimesheetTemplate" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-md" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title text-primary">Timesheet Template Download Xlsx</h5>
                  </div>

                  <form action="<?= base_url('transaction/timesheet_download/downloadTimesheet') ?>" method="get" target="_blank">
                      <div class="modal-body">
                          <div class="row">
                              <div class="col-12 mb-3">
                                  <label class="col-form-label fw-bold">Client:</label>
                                  <select class="form-control" name="clientName" id="client" required>
                                      <option value="" disabled selected>Pilih</option>
                                      <?php if (!empty($clients)): ?>
                                          <?php foreach ($clients as $client): ?>
                                              <option value="<?= esc($client['client_value']) ?>">
                                                  <?= esc($client['client_name']) ?>
                                              </option>
                                          <?php endforeach; ?>
                                      <?php else: ?>
                                          <option value="" disabled>Tidak ada client tersedia</option>
                                      <?php endif; ?>
                                  </select>
                              </div>


                              <div class="col-12 mb-3">
                                  <label class="col-form-label fw-bold">Year:</label>
                                  <select class="form-control" name="year" id="year" required>
                                      <option value="" disabled selected>Pilih</option>
                                      <?php for ($i = date('Y') + 1; $i >= date('Y') - 4; $i--): ?>
                                          <option value="<?= $i ?>"><?= $i ?></option>
                                      <?php endfor; ?>
                                  </select>
                              </div>

                              <div class="col-12 mb-3">
                                  <label class="col-form-label fw-bold">Month:</label>
                                  <select class="form-control" name="month" id="month" required>
                                      <option value="" disabled selected>Pilih</option>
                                      <?php for ($i = 1; $i <= 12; $i++): ?>
                                          <option value="<?= sprintf('%02d', $i) ?>"><?= sprintf('%02d', $i) ?></option>
                                      <?php endfor; ?>
                                  </select>
                              </div>

                              <!-- Contoh tambahan field textarea seperti yang kamu sebut -->
                          </div>
                      </div>

                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">
                              <i class="fa fa-download me-1"></i> Download Timesheet
                          </button>
                      </div>
                  </form>
              </div>
          </div>
      </div>


      <div class="modal modal-blur fade" id="modalAllowanceTemplate" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-md" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title text-primary">Allowance Template Download Xlsx</h5>
                  </div>

                  <form action="<?= base_url('transaction/timesheet_download/downloadAllowance') ?>" method="get" target="_blank">
                      <div class="modal-body">
                          <div class="row">
                              <div class="col-12 mb-3">
                                  <label class="col-form-label fw-bold">Client:</label>
                                  <select class="form-control" name="clientName" id="client" required>
                                      <option value="" disabled selected>Pilih</option>
                                      <?php if (!empty($clients)): ?>
                                          <?php foreach ($clients as $client): ?>
                                              <option value="<?= esc($client['client_value']) ?>">
                                                  <?= esc($client['client_name']) ?>
                                              </option>
                                          <?php endforeach; ?>
                                      <?php else: ?>
                                          <option value="" disabled>Tidak ada client tersedia</option>
                                      <?php endif; ?>
                                  </select>
                              </div>

                              <div class="col-12 mb-3">
                                  <label class="col-form-label fw-bold">Year:</label>
                                  <select class="form-control" name="year" id="year" required>
                                      <option value="" disabled selected>Pilih</option>
                                      <?php for ($i = date('Y') + 1; $i >= date('Y') - 4; $i--): ?>
                                          <option value="<?= $i ?>"><?= $i ?></option>
                                      <?php endfor; ?>
                                  </select>
                              </div>

                              <div class="col-12 mb-3">
                                  <label class="col-form-label fw-bold">Month:</label>
                                  <select class="form-control" name="month" id="month" required>
                                      <option value="" disabled selected>Pilih</option>
                                      <?php for ($i = 1; $i <= 12; $i++): ?>
                                          <option value="<?= sprintf('%02d', $i) ?>"><?= sprintf('%02d', $i) ?></option>
                                      <?php endfor; ?>
                                  </select>
                              </div>

                              <!-- Contoh tambahan field textarea seperti yang kamu sebut -->
                          </div>
                      </div>

                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">
                              <i class="fa fa-download me-1"></i> Download Timesheet
                          </button>
                      </div>
                  </form>
              </div>
          </div>
      </div>

      <!-- Tambahkan di bagian atas sebelum script custom -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
          $(document).ready(function() {
              $('#uploadRoster').click(function(e) {
                  e.preventDefault();

                  $("#spinner").show();
                  $('#uploadRoster').prop('disabled', true);

                  var formData = new FormData($('#rosterUploadForm')[0]);
                  var fileName = $('#file').val();

                  // === CI4: gunakan site_url untuk endpoint ===
                  var myUrl = "<?= base_url('transaction/Timesheet/upload'); ?>";

                  // === Tambahkan CSRF Token CI4 ===
                  formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                  $.ajax({
                      type: 'POST',
                      url: myUrl,
                      data: formData,
                      processData: false,
                      contentType: false,
                      success: function(response) {
                          Swal.fire({
                              title: "Success!",
                              text: response.message,
                              icon: "success"
                          });

                          if (typeof getConfetti === "function") getConfetti();

                          $("#spinner").hide();
                          $('#uploadRoster').prop('disabled', false);
                          $('#file').val(''); // reset input
                      },
                      error: function(xhr) {
                          console.log(xhr);

                          let msg = 'Upload gagal. Silakan periksa file.';
                          if (xhr.responseJSON && xhr.responseJSON.errors) {
                              msg = JSON.stringify(xhr.responseJSON.errors);
                          } else if (xhr.responseJSON && xhr.responseJSON.message) {
                              msg = xhr.responseJSON.message;
                          }

                          Swal.fire({
                              title: "Failed!",
                              text: msg,
                              icon: "error"
                          });

                          $("#spinner").hide();
                          $('#uploadRoster').prop('disabled', false);
                      }
                  });
              });



              $('#uploadAllowance').click(function(e) {
                  e.preventDefault();

                  $("#spinner").show();
                  $('#uploadAllowance').prop('disabled', true);

                  var formData = new FormData($('#allowanceUploadForm')[0]);
                  var fileName = $('#file').val();

                  // === CI4: gunakan site_url untuk endpoint ===
                  var myUrl = "<?= base_url('transaction/Allowance/upload'); ?>";

                  // === Tambahkan CSRF Token CI4 ===
                  formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                  $.ajax({
                      type: 'POST',
                      url: myUrl,
                      data: formData,
                      processData: false,
                      contentType: false,
                      success: function(response) {
                          Swal.fire({
                              title: "Success!",
                              text: response.message,
                              icon: "success"
                          });

                          if (typeof getConfetti === "function") getConfetti();

                          $("#spinner").hide();
                          $('#uploadAllowance').prop('disabled', false);
                          $('#file').val(''); // reset input
                      },
                      error: function(xhr) {
                          console.log(xhr);

                          let msg = 'Upload gagal. Silakan periksa file.';
                          if (xhr.responseJSON && xhr.responseJSON.errors) {
                              msg = JSON.stringify(xhr.responseJSON.errors);
                          } else if (xhr.responseJSON && xhr.responseJSON.message) {
                              msg = xhr.responseJSON.message;
                          }

                          Swal.fire({
                              title: "Failed!",
                              text: msg,
                              icon: "error"
                          });

                          $("#spinner").hide();
                          $('#uploadAllowance').prop('disabled', false);
                      }
                  });
              });


          });
      </script>




      <script src="<?php echo base_url() ?>/assets/js/main.js"> </script>