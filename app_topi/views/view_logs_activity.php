<!DOCTYPE html>
<html>
    <title>Log Aktivitas Pengguna</title>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.0.3/js/dataTables.dateTime.min.js"></script> -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.3.1/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/cr-1.5.3/date-1.0.3/fc-3.3.2/fh-3.1.8/kt-2.6.1/r-2.2.7/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.3/datatables.min.css"/>
 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.3.1/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/cr-1.5.3/date-1.0.3/fc-3.3.2/fh-3.1.8/kt-2.6.1/r-2.2.7/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.3/datatables.min.js"></script>

</head>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box box-solid">
                <div class="box-header with-border">
                    <h4>Log Aktivitas Pengguna</h4>
                </div>
                <div class="box-body">
                    <body>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <label for="user" class="col-sm-2">Pilih User</label>
                                    <select name="user" id="user" class="col-sm-1">
                                        <option value="0">(Semua)</option>
                                        <?php foreach ($user as $key => $value) {?><option value="<?php echo $value->id ?>"><?php echo $value->nama ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="row">
                                    <label for="input-daterange" class="col-sm-2">Tanggal Akses: </label>
                                    <div class="input-daterange">
                                        <input type="text" name="start_date" id="start_date" /> - <input type="text" name="end_date" id="end_date"  />
                                    </div>
                                </div>
                                <br>
                                <div class="table-responsive">
                                    <table id="tabel_data" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Username</th>
                                                <th>Uri</th>
                                                <th>Parameter (NIK/KK)</th>
                                                <th>API Key</th>
                                                <th>IP Address</th>
                                                <th>Terakhir Akses</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Username</th>
                                            <th>Uri</th>
                                            <th>Parameter (NIK/KK)</th>
                                            <th>API Key</th>
                                            <th>IP Address</th>
                                            <th>Terakhir Akses</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </body>
                </div>
            </div>
        </div>
    </section>
</html>
<script type="text/javascript">
var table;
var startDate;
var endDate;
var user;
$(document).ready(function() {
    table = $("#tabel_data")
    startDate = $("input#start_date")
    endDate = $("input#end_date")
    user = $("#user")
    // // Create date inputs
    minDate = new DateTime(startDate, {
        format: 'YYYY-MM-DD'
    });
    maxDate = new DateTime(endDate, {
        format: 'YYYY-MM-DD'
    });

    let dataTable = table.DataTable({
        "processing": true,
        "serverSide": true,
        'serverMethod': 'post',
        "order": [],
        "ajax": {
            "url": "<?php echo base_url()."admin/logs_activity_json";?>",
            "type": "POST",
            "data": function(d){
                d.start_date = startDate.val()
                d.end_date = endDate.val()
                d.user = user.val()
            },
        },
        dom: 'Blfrtip',
        buttons: ['copy',
            { extend:'csv', title: 'Log Aktivitas Pengguna' },
            //{ extend:'pdf', title: 'Log Aktivitas Pengguna' },
            { extend:'excel', title: 'Log Aktivitas Pengguna' },
            'print',
        ],
        "columnDefs": [],
    });

    let redraw = function(){
        dataTable.ajax.reload();
    }
    user.on("change", redraw)
    startDate.on("change", redraw)
    endDate.on("change", redraw)
});
</script>