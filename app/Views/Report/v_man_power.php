<?php 
$dept_active        = 'active';
$month_active       = '';
$dept_active_tab    = 'show active';
$month_active_tab   = '';
if ($request->getPost('thn')) {
    $dept_active        = '';
    $month_active       = 'active';
    $dept_active_tab    = '';
    $month_active_tab   = 'show active';
}
?>
<div class="card">
    <div class="card-header bg-white border-0">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link <?php echo $dept_active;?>" id="nav-home-tab" data-toggle="tab"
                    href="#nav-home" role="tab" aria-controls="nav-home">Department</a>
            </div>
        </nav>
    </div>
    <div class="card-body">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade <?php echo $dept_active_tab;?>" id="nav-home" role="tabpanel"
                aria-labelledby="nav-home-tab">
                <canvas id="dept-chart"></canvas>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Dept</label>
                            <select class="form-control" name="dept" id="dept">
                                <option selected>ALL</option>
                                <?php foreach($mst_dept as $r) {?>
                                <option><?php echo $r->dept_name;?></option>
                                <?php }?>
                                <option>Non Active</option>
                            </select>
                        </div>
                    </div>
                </div>
                <table class="table" id="tableManPower">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Dept</th>
                            <th>Position</th>
                            <th style="display:none;">#</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url()?>/assets/js/main.js"></script>
<script src="<?php echo base_url();?>/assets/js/plugins/Chart.min.js"></script>
<!-- <script src="<?php echo base_url();?>/assets/js/plugins/Chart-plugin.js"></script> -->
<script>
$(document).ready(function(e) {
    /* START USERS TABLE */
    var Table = $('#tableManPower').DataTable({
        "responsive": true,
        "bDestroy": true,
        "columnDefs": [{
            "targets": -1,
            "data": null,
            "defaultContent": ""
        }],
    });
    /* END USERS TABLE */
    $('#dept').on("change", function() {
        var dept = $('#dept').val();
        runData(Table, dept);
    });
    runData(Table, 'ALL');

    function runData(Table, dept) {
        $.ajax({
            method: "POST",
            url: "<?php echo base_url('report/man_power/data')?>",
            data: {
                dept: dept
            },
            success: function(data) {
                Table.clear().draw();
                var srcData = JSON.parse(data);
                Table.rows.add(srcData).draw(false);
            },
            error: function(data) {
                alert('Data failed Load');
            }
        });
    }

    var options = {
        title: {
            display: true,
            text: 'Overview',
            fontStyle: 'bold',
            fontSize: 20
        },
        plugins: {
            colors: {
                forceOverride: true
            },
            // datalabels: {
            //     formatter: (value, ctx) => {
            //         let sum = 0;
            //         let dataArr = ctx.chart.data.datasets[0].data;
            //         dataArr.map(data => {
            //             sum += data;
            //         });
            //         let percentage = (value * 100 / sum).toFixed(2) + "%";
            //         return percentage;
            //     },
            //     color: 'yellow',
            // },

        }
    };
    // var colors = [
    //     '#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263',
    //     '#6AF9C4', '#007bff', '#6610f2', '#6f42c1', '#e83e8c', '#dc3545', '#fd7e14',
    //     '#20c997', '#343a40', '#009688', '#6c757d', '#28a745', '#17a2b8', '#ffc107', '#3e1717',
    //     '#eb3434', '#eb5634', '#eb9334', '#ebbd34', '#dfeb34', '#a2eb34', '#59eb34',
    //     '#34eb6e', '#34ebd9', '#34c6eb', '#348ceb', '#346beb', '#3446eb', '#4034eb', '#8c34eb',
    //     '#b134eb', '#d634eb', '#eb349c', '#801b1b', '#80651b', '#63801b', '#3d801b',
    //     '#1b8039', '#1b8078', '#1b5480', '#1b3180', '#3b1b80', '#631b80', '#801b78', '#801b51',
    //     '#785050', '#786c50', '#6a7850', '#537850', '#507869', '#506b78', '#555078'
    // ];
    var colors = ['aqua', 'blue', 'fuchsia', 'gray', 'green',
        'lime', 'maroon', 'navy', 'olive', 'orange', 'purple', 'red',
        'teal', 'yellow'
    ];
    // var colors = ['red', 'green', 'blue', 'orange', 'yellow'];

    Array.prototype.getRandom = function(cut) {
        var i = Math.floor(Math.random() * this.length);
        if (cut && i in this) {
            return this.splice(i, 1)[0];
        }
        return this[i];
    }

    var piechart = document.getElementById('dept-chart');
    var bgColor = [<?php foreach ($mst_dept as $r) { echo "'#".substr(md5(rand()), 0, 6)."',"; }?>];
    var chart = new Chart(piechart, {
        type: 'pie',
        data: {
            labels: [
                <?php
                $countAll = $db->query('SELECT count(*) as jml FROM mt_biodata_01')->getRow();
                foreach($mst_dept as $r) {
                    $countBio = $db->query('SELECT count(*) as jml FROM mt_biodata_01 WHERE dept = ? AND is_active = 1', [ $r->dept_name ])->getRow();
                    $persen = round(($countBio->jml / $countAll->jml) * 100);
                    echo "'$r->dept_name ($persen%) ',";
                }
                $nonActive = $db->query('SELECT count(*) as jml FROM mt_biodata_01 WHERE is_active = 0')->getRow();
                $persen = round(($nonActive->jml / $countAll->jml) * 100);
            ?>
            'Non Active (<?= $persen ?>%)'
            ], // Merubah data tanggal menjadi format JSON
            datasets: [{
                label: 'Jumlah',
                data: [
                    <?php
                        foreach ($mst_dept as $r) {
                            $countBio = $db->query('SELECT count(*) as jml FROM mt_biodata_01 WHERE dept = ? AND is_active = 1', [ $r->dept_name ])->getRow();
                            echo $countBio->jml.',';
                        }
                        $nonActive = $db->query('SELECT count(*) as jml FROM mt_biodata_01 WHERE is_active = 0')->getRow();
                    ?>
                    '<?= $nonActive->jml ?>'
                ],
                backgroundColor: bgColor,
                borderColor: "#fff",
                borderWidth: 0,
                hoverBorderColor: bgColor,
                hoverBorderWidth: 5,
                cutout: 0
            }, ],
        },
        options: options
    });

    function getRandomColor() {
        var letters = '0123456789ABCDEF'.split('');
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
});
</script>