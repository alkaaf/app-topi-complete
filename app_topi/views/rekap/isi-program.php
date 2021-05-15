<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<table id="dgUrusan" class="easyui-datagrid"
        data-options="rownumbers:true,
            singleSelect:true,
            fit:true,
            bodyCls:'bg-grid',
            url:'<?php echo base_url(); ?><?php echo $link ?>',
            method:'get',
            remoteSort:true">
    <thead>
        <tr>
            <th rowspan="2" data-options="sortable:true,field:'skpd',width:400">Nama SKPD</th>
            <th rowspan="2" data-options="sortable:true,field:'jml_program',align:'right',width:100">Jml Program</th>
            <th rowspan="2" data-options="sortable:true,field:'indikator_sudah_di_isi',align:'right',width:140">Indikator Terisi</th>
            <th colspan="4">Target Terisi</th>
            <th colspan="4">Realisasi Terisi</th>
        </tr>
        <tr>
            <th data-options="sortable:true,field:'tt1',align:'right',width:70">TW1</th>
            <th data-options="sortable:true,field:'tt2',align:'right',width:70">TW2</th>
            <th data-options="sortable:true,field:'tt3',align:'right',width:70">TW3</th>
            <th data-options="sortable:true,field:'tt4',align:'right',width:70">TW4</th>
            <th data-options="sortable:true,field:'rt1',align:'right',width:70">TW1</th>
            <th data-options="sortable:true,field:'rt2',align:'right',width:70">TW2</th>
            <th data-options="sortable:true,field:'rt3',align:'right',width:70">TW3</th>
            <th data-options="sortable:true,field:'rt4',align:'right',width:70">TW4</th>
        </tr>
    </thead>
</table>
<div id="ftUrusan" style="padding:2px 5px;">
    <a href="#" id="btnSink" class="easyui-linkbutton" iconCls="fa fa-refresh" plain="true"> Sinkronkan Data</a>
</div>
<div id="domMessage" style="display:none;"> 
    <h3>Please Wait .............</h3> 
</div>
<script type="text/javascript">
    $.parser.parse();
    $("#dgUrusan").datagrid('enableFilter');

    $('#btnSink').click(function(){
        $.blockUI({ message: $('#domMessage') }); 
        $.post(base_url + 'p/sink/urusan')
        .done(function(data){
            $("#dgUrusan").datagrid('reload');
        })
    });
</script>