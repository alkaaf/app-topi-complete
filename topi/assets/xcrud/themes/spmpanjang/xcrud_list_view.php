<!--
  /**
	 * @author entol
	 * @see more  http://www.entol.net
     * @see Follow https://plus.google.com/+Entolfakih  @entol_net
     */ 

 -->
<section class="content">
<?php echo $this->render_table_name(); ?>
<?php if ($this->is_create or $this->is_csv or $this->is_print){?>
        <div class="xcrud-top-actions">
            <div class="btn-group pull-right">
            
            <?php echo $this->render_search(); ?>
                <?php echo $this->print_button('btn btn-flat btn-default','glyphicon glyphicon-print');?>
               
              <?php // echo $this->excel_button('btn btn-default','glyphicon glyphicon-export');
								//echo $this->csv_button('btn btn-default','glyphicon glyphicon-file'); ?>
								 
            </div>
            <?php echo $this->add_button('btn btn-flat btn-success','glyphicon glyphicon-pencil'); ?>
             
            <div class="clearfix"> </div>
        </div>
<?php } ?>
        
       <div class="box-body table-responsive no-padding">
        <table class="table table-striped table-hover ">
            <thead class="" >
                <?php echo $this->render_grid_head(); ?>
            </thead>
            <tbody>
                <?php echo $this->render_grid_body(); ?>
            </tbody>
            <tfoot>
                <?php echo $this->render_grid_footer(); ?>
            </tfoot>
        </table>
        
        </div><hr>
        <div class="pull-left">
         <?php echo $this->render_benchmark(); ?>
        </div>
        <div class="clearfix">
        <span class="xcrud-nav"><?php echo $this->render_limitlist();?>  Data per Halaman </span>
         <span class="pull-right"><?php echo $this->render_pagination(); ?></span>
         
        </div>
		</section>
