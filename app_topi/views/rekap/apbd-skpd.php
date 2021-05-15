<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<table id="pvData"></table>
<div id="domMessage" style="display:none;"> 
    <h3>Please Wait .............</h3> 
</div>
<script type="text/javascript">
    $('#pvData').pivotgrid({
        url:'<?php echo base_url(); ?>api/keuangan_per_skpd',
        method:'get',
        fit:true,
        pivot:{
            rows:['Nama','Program','Kegiatan'],
            columns:['tahun'],
            values:[
                {field:'Murni',op:'sum'},
                {field:'Perubahan',op:'sum'}
            ]
        },
        
        valuePrecision:0
    })
</script>