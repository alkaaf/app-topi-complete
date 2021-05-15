<ul class="sidebar-menu">
  


  <li class="header">User Area</li>
        
    <li><a href="<?php echo base_url('download'); ?>"><i class="fa fa-file"></i> <span>Download Data</span></a></li>
    
  

  <?php  if ($this->aauth->is_admin()) : ?>
  <li class="header">ADMINISTRASI</li>
  <li><a href="<?php echo base_url('admin/logs'); ?>"><i class="fa fa-search"></i> <span>Log Data</span></a></li>
  <li><a href="<?php echo base_url('admin/logs_activity'); ?>"><i class="fa fa-history"></i> <span>Log Aktivitas</span></a></li>
  <li class="treeview">
    <a href="#">
      <i class="fa fa-folder"></i> <span>Setting Data</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
      <li><a href="<?php echo base_url('admin/kolom'); ?>"><i class="fa fa-file"></i> <span>Master Kolom</span></a></li>
      <li><a href="<?php echo base_url('admin/individu'); ?>"><i class="fa fa-file"></i> <span>Master Tabel Individu</span></a></li>
      <li><a href="<?php echo base_url('admin/tabel'); ?>"><i class="fa fa-file"></i> <span>Master Tabel Rekap</span></a></li>
      <li><a href="<?php echo base_url('admin/kartukeluarga'); ?>"><i class="fa fa-file"></i> <span>Master Tabel Kartu Keluarga</span></a></li>
      <!-- <li><a href="<?php echo base_url('admin/api'); ?>"><i class="fa fa-file"></i> <span>Master API</span></a></li> -->
    </ul>
  </li>
  <li class="treeview">
    <a href="#">
      <i class="fa fa-folder"></i> <span>User & Group</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
      <li><a href="<?php echo base_url('admin/user'); ?>"><i class="fa fa-user"></i> <span>Manajemen User</span></a></li>
      <li><a href="<?php echo base_url('admin/group'); ?>"><i class="fa fa-group"></i> <span>Manajemen Group</span></a></li>
    
      <!-- <li><a href="<?php echo base_url('admin/rekening'); ?>"><i class="fa fa-file"></i> <span>Master Rekening</span></a></li> -->
    </ul>
  </li>
  <?php endif; ?>
</ul>