<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box box-solid">
                <?php if (isset($judul)) : ?>
                <div class="box-header with-border">
                  <h3 class="box-title"><?php echo $judul; ?></h3> <?php if (isset($linkback)) : ?><?php echo $linkback; ?><?php endif; ?>
                </div>
                <?php endif; ?>
                <div class="box-body">
                    <?php echo $content; ?>
                </div>
                
                
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">

jQuery(document).on("xcrudafterrequest",function(event,container){
    if(Xcrud.current_task == 'save')
    {
        <?php if (isset($kembali)) : ?>
        window.location = '<?php echo $kembali; ?>';
        <?php else :?>
        location.reload();
        <?php endif ?>
    }
});
</script>