<div id="spinner" style="display:none;">
  <div class="loading"></div>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="tile">
      <h3 class="tile-title">Import Timesheet & Allowance</h3>
      <div class="tile-footer">
        <?php echo form_open_multipart('../Transaction/Upload/Upload', ['id' => 'uploadForm']) ?>
        <label><b>Import Timesheet</b></label>
        <input name="fileimport" type="file" class="form-control" accept=".xls, .xlsx">
        <br>
        <div class="form-group">
          <button type="button" class="btn btn-success" id="submitBtn">Proses Import Timesheet</button>
        </div>
        <?php echo form_close(); ?>
        <br><br>


        <?php echo form_open_multipart('../Transaction/Allowance/Upload', ['id' => 'uploadAllowanceForm']) ?>
        <label> <b>Import Allowance</b></label>
        <input name="file" type="file" class="form-control" accept=".xls, .xlsx">
        <br>
        <div class="form-group">
          <button type="button" class="btn btn-success" id="submitBtnAllowance">Proses Import Allowance</button>
        </div>
        <?php echo form_close(); ?>
        <br><br>

        <!-- TABLE -->
        <div class="table-responsive">
          <table class="table table-hover table-bordered" id="upload">
            <thead style="background-color: rgb(13 81 198);color: white;">
              <tr>
                <th>Biodata Id</th>
                <th>Full Name</th>
                <th>Dept</th>
              </tr>
            </thead>
            <tbody id="tableBody">
              <!-- Data dari server -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  document.getElementById('submitBtn').addEventListener('click', function() {
    const form = document.getElementById('uploadForm');
    const formData = new FormData(form);
    const spinner = document.getElementById('spinner');

    spinner.style.display = 'flex';

    // Kirim data dengan AJAX
    fetch(form.action, {
        method: 'POST',
        body: formData
      })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text(); // Ambil sebagai teks mentah
      })
      .then(response => {
        var data = JSON.parse(response);
        // debugger
        // console.log(data);
        spinner.style.display = 'none';

        if (data.success) {
          alert('Data Berhasil Di Import !!');
          updateTable(data.rows); // Update tabel dengan data baru
        } else {
          alert('Terjadi kesalahan: ' + data.error);
        }
      })
      .catch(error => {
        var test = error;
        console.log(error);
        // debugger
        spinner.style.display = 'none';
        alert('Error: ' + error.message);
      });
  });

  // Fungsi untuk memperbarui tabel
  function updateTable(rows) {
    // debugger
    const tableBody = document.getElementById('tableBody');
    tableBody.innerHTML = ''; // Kosongkan tabel

    rows.forEach((row, index) => {
      if (index > 1) {
        const tr = document.createElement('tr');
        // console.log(row);
        tr.innerHTML = `
        <td>${row['0']}</td>
        <td>${row['2']}</td>
        <td>${row['3']}</td>
      `;
        tableBody.appendChild(tr);
      }
    });
  }
</script>

<script src="<?php echo base_url() ?>/assets/js/main.js">
  $('#select_all').on('click', function() {
    if (this.checked) {
      $('.checkbox').each(function() {
        this.checked = true;
      });
    } else {
      $('.checkbox').each(function() {
        this.checked = false;
      });
    }
  });

  $('.checkbox').on('click', function() {
    if ($('.checkbox:checked').length == $('.checkbox').length) {
      $('#select_all').prop('checked', true);
    } else {
      $('#select_all').prop('checked', false);
    }
  });

  $('#select_all2').on('click', function() {
    if (this.checked) {
      $('.checkbox2').each(function() {
        this.checked = true;
      });
    } else {
      $('.checkbox2').each(function() {
        this.checked = false;
      });
    }
  });

  $('.checkbox2').on('click', function() {
    if ($('.checkbox2:checked').length == $('.checkbox2').length) {
      $('#select_all2').prop('checked', true);
    } else {
      $('#select_all2').prop('checked', false);
    }
  });
  var data = {
    labels: ["January", "February", "March", "April", "May"],
    datasets: [{
        label: "My First dataset",
        fillColor: "rgba(220,220,220,0.2)",
        strokeColor: "rgba(220,220,220,1)",
        pointColor: "rgba(220,220,220,1)",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(220,220,220,1)",
        data: [65, 59, 80, 81, 56]
      },
      {
        label: "My Second dataset",
        fillColor: "rgba(151,187,205,0.2)",
        strokeColor: "rgba(151,187,205,1)",
        pointColor: "rgba(151,187,205,1)",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(151,187,205,1)",
        data: [28, 48, 40, 19, 86]
      }
    ]
  };
  var pdata = [{
      value: 300,
      color: "#46BFBD",
      highlight: "#5AD3D1",
      label: "Complete"
    },
    {
      value: 50,
      color: "#F7464A",
      highlight: "#FF5A5E",
      label: "In-Progress"
    }
  ]

  var ctxl = $("#lineChartDemo").get(0).getContext("2d");
  var lineChart = new Chart(ctxl).Line(data);

  var ctxp = $("#pieChartDemo").get(0).getContext("2d");
  var pieChart = new Chart(ctxp).Pie(pdata);
</script>

<script>
  if (typeof jQuery !== "undefined") {
    console.log("jQuery tersedia");
  } else {
    console.log("jQuery TIDAK tersedia");
  }
</script>



<script>
  $(document).ready(function() {

    $('#submitBtnAllowance').on('click', function(e) {
      e.preventDefault();

      const form = $('#uploadAllowanceForm')[0];
      const formData = new FormData(form);
      const spinner = $('#spinner');

      spinner.css('display', 'flex');

      $.ajax({
        url: $(form).attr('action'),
        type: 'POST',
        data: formData,
        processData: false, // WAJIB untuk FormData
        contentType: false, // WAJIB untuk FormData
        success: function(response) {
          spinner.hide();
          console.log(response);

          Swal.fire({
            title: "Success!",
            text: response.message,
            icon: "success"
          });



          // let data;
          // try {
          //   data = JSON.parse(response);
          // } catch (e) {
          //   alert('Response bukan JSON valid');
          //   console.error(response);
          //   return;
          // }

          // if (data.success) {
          //   alert('Data Berhasil Di Import !!');
          // updateTable(response.data);
          updateTable(response.data.slice(2));

        },
        error: function(xhr, status, error) {
          spinner.hide();
          console.error(error);
          alert('Error: ' + error);
        }
      });
    });

  });

  /* ===============================
     Update Tabel (jQuery)
  ================================ */
  function updateTable(rows) {
    const tableBody = $('#tableBody');
    tableBody.empty();

    $.each(rows, function(index, row) {
      if (index > 1) {
        const tr = `
                <tr>
                    <td>${row['0']}</td>
                    <td>${row['2']}</td>
                    <td>${row['3']}</td>
                </tr>
            `;
        tableBody.append(tr);
      }
    });
  }
</script>