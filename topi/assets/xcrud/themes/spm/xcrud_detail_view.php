
<section class="content">
<div class="xcrud-top-actions btn-group pull-right">
    <?php 
  
    echo $this->render_button('save_return','save','list','btn bg-purple btn-flat','','create,edit');
    
    echo $this->render_button('save_edit','save','edit','btn btn-default btn-flat','','create,edit');
    echo $this->render_button('return','list','','btn bg-orange btn-flat'); ?>
</div>
<?php echo $this->render_table_name($mode); ?>
<div class="xcrud-view">
<?php echo $mode == 'view' ? $this->render_fields_list($mode,array('tag'=>'table','class'=>'table table-striped')) : $this->render_fields_list($mode,'div','div','label','div'); ?>
</div>
<div class="xcrud-nav">
<?php echo $this->render_button('save_new','save','create','btn btn-default pull-right btn-flat','','create,edit');?>
  
</div>
<div class="pull-left"> <?php echo $this->render_benchmark(); ?> </div>
</section>