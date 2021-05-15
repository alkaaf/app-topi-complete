<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<table id="dgUrusan" class="easyui-datagrid"
        data-options="rownumbers:true,
            singleSelect:true,
            fit:true,
            bodyCls:'bg-grid',
            url:'<?php echo base_url(); ?>api/keuangan_all_skpd',
            method:'get',
            remoteSort:true,
            footer:'#ftUrusan'">
    <thead>
        <tr>
            <th data-options="sortable:true,field:'skpd',width:400">Nama SKPD</th>
            <th data-options="sortable:true,field:'pagu',align:'right', 
                      formatter: function(value,row,index){
                              if (row.pagu){
                                return accounting.formatNumber(row.pagu, 2, ',', '.');
                              } else {
                                return value;
                              }
                            },width:150">Pagu Anggaran</th>
            <th data-options="field:'target',align:'right', 
                      formatter: function(value,row,index){
                              if (row.target){
                                return accounting.formatNumber(row.target, 2, ',', '.');
                              } else {
                                return value;
                              }
                            },width:100">Target</th>
            <th data-options="field:'realisasi',align:'right', 
                      formatter: function(value,row,index){
                              if (row.realisasi){
                                return accounting.formatNumber(row.realisasi, 2, ',', '.');
                              } else {
                                return value;
                              }
                            },width:100">Realisasi</th>
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