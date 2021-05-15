<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie ie6 lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="ie ie7 lt-ie9 lt-ie8"        lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="ie ie8 lt-ie9"               lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="ie ie9"                      lang="en"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-ie">
<!--<![endif]-->
<head>
   <!-- Meta-->
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
   <meta name="description" content="">
   <meta name="keywords" content="">
   <meta name="author" content="">
   <title><?php echo $page->title; ?></title>
   <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries-->
   <!--[if lt IE 9]><script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script><script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script><![endif]-->
   <!-- Bootstrap CSS-->
   <link rel="stylesheet" href="<?php echo base_url() ?>app/css/bootstrap.css">
   <!-- Vendor CSS-->
   <link rel="stylesheet" href="<?php echo base_url() ?>vendor/font-awesome/css/font-awesome.min.css">
   <link rel="stylesheet" href="<?php echo base_url() ?>vendor/animo.js/animate-animo.css">
   <link rel="stylesheet" href="<?php echo base_url() ?>vendor/whirl/dist/whirl.css">
   <!-- START Page Custom CSS-->
   <!-- END Page Custom CSS-->
   <!-- App CSS-->
   <link rel="stylesheet" href="<?php echo base_url() ?>app/css/easyui.css">
   <link rel="stylesheet" href="<?php echo base_url() ?>app/css/app.css">
   <link rel="stylesheet" href="<?php echo base_url() ?>app/css/icon.css">
   <!-- Modernizr JS Script-->

   <script src="<?php echo base_url() ?>vendor/modernizr/modernizr.custom.js" type="application/javascript"></script>
   <!-- FastClick for mobiles-->
   <script src="<?php echo base_url() ?>vendor/fastclick/lib/fastclick.js" type="application/javascript"></script>

   <!-- Main vendor Scripts-->
   <script src="<?php echo base_url() ?>vendor/jquery/dist/jquery.min.js"></script>
   <script src="<?php echo base_url() ?>app/js/jquery.easyui.min.js"></script>
   <script src="<?php echo base_url() ?>app/js/pdfobject.js"></script>
   <script src="<?php echo base_url() ?>vendor/bootstrap/dist/js/bootstrap.min.js"></script>

   <script type="text/javascript">
      var wHeight = window.innerHeight;
      var wWidth = window.innerWidth;
      function calculateHeightCurrentWindow() {
         wHeight = window.innerHeight;
         wWidth = window.innerWidth;
      }
      $( document ).ready(function() {
         $(window).on('resize', function(){
            calculateHeightCurrentWindow();
         })
      });

      function alexFormatter(date){
          var y = date.getFullYear();
          var m = date.getMonth()+1;
          var d = date.getDate();
          return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
      }
      function alexParser(s){
          if (!s) return new Date();
          var ss = (s.split('-'));
          var y = parseInt(ss[0],10);
          var m = parseInt(ss[1],10);
          var d = parseInt(ss[2],10);
          if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
              return new Date(y,m-1,d);
          } else {
              return new Date();
          }
      }
      if (typeof base_url == 'undefined') {
        var base_url = '<?php echo base_url(); ?>';
      }

      
   </script>
</head>


<body class="aside-collapsed">
   <!-- START Main wrapper-->
   <div class="wrapper">
      <!-- START Top Navbar-->
      <nav role="navigation" class="navbar navbar-default navbar-top navbar-fixed-top">
         <!-- START navbar header-->
         <div class="navbar-header">
            <a href="<?php echo base_url() ?>" class="navbar-brand">
               <div class="brand-logo">
                  <img src="<?php echo base_url() ?>app/img/logo.png" alt="App Logo" class="img-responsive">
               </div>
               <div class="brand-logo-collapsed">
                  <img src="<?php echo base_url() ?>app/img/logo-single.png" alt="App Logo" class="img-responsive">
               </div>
            </a>
         </div>
         <!-- END navbar header-->
         <!-- START Nav wrapper-->
         <div class="nav-wrapper">
            <!-- START Left navbar-->
            <ul class="nav navbar-nav">
               <!-- START Messages menu (dropdown-list)-->
               <li class="dropdown dropdown-list">
                  <a href="#" data-toggle="dropdown" data-play="flipInX" class="dropdown-toggle">
                     <em class="fa fa-bell"></em>
                     <!-- <div class="label label-danger">0</div> -->
                  </a>
                  <!-- START Dropdown menu-->
                  <ul class="dropdown-menu">
                     <li class="dropdown-menu-header">Pemberitahuan</li>
                     <li>
                        <div class="scroll-viewport">
                           <!-- START list group-->
                           <div class="list-group scroll-content">
                            
                           </div>
                           <!-- END list group-->
                        </div>
                     </li>
                     <!-- START dropdown footer-->
                     
                     <!-- END dropdown footer-->
                  </ul>
                  <!-- END Dropdown menu-->
               </li>
               <!-- END Messages menu (dropdown-list)-->
               <!-- START User avatar toggle-->
               <li>
                  <!-- Button used to collapse the left sidebar. Only visible on tablet and desktops-->
                  <a href="<?php echo base_url() ?>account/profil">
                     <em class="fa fa-user"></em>
                  </a>
               </li>
               <!-- END User avatar toggle-->
               <!-- START User avatar toggle-->
               <li>
                  <!-- Button used to collapse the left sidebar. Only visible on tablet and desktops-->
                  <a href="<?php echo base_url('auth/logout') ?>">
                     <em class="fa fa-power-off"></em>
                  </a>
               </li>
               <!-- END User avatar toggle-->
            </ul>
            <!-- END Left navbar-->
            <!-- START Right Navbar-->
            <ul class="nav navbar-nav navbar-right">
               <!-- <li>
                  <a href="#" data-toggle-state="offsidebar-open">
                     <em class="fa fa-group"></em>
                  </a>
               </li> -->
               <!-- END Contacts menu-->
            </ul>
            <!-- END Right Navbar-->
         </div>
         <!-- END Nav wrapper-->
         <!-- END Search form-->
      </nav>
      <!-- END Top Navbar-->
      <section>

      <div class="easyui-layout" data-options="fit:true">
          
          <div data-options="region:'west',split:true,hideCollapsedContent:false,collapsed:false" title="Main Menu" class="bgmenu" style="min-width:100px;width:20%;max-width: 300px;">
            
          <ul id="tMn">
            
          </ul>

          </div>
          <div id="content-block" data-options="region:'center',title:'Dashboard'">
          
          </div>
      </div>

         </section>
         <!-- END Main section-->
      </div> <!-- START Scripts-->
         <div id="dlg"></div>
         <div id="dlg1"></div>
         <div id="dlg2"></div>
         <!-- Plugins-->
         <script src="<?php echo base_url() ?>vendor/chosen/chosen.jquery.js"></script>
         <script src="<?php echo base_url() ?>vendor/seiyria-bootstrap-slider/dist/bootstrap-slider.min.js"></script>
         <script src="<?php echo base_url() ?>vendor/bootstrap-filestyle/src/bootstrap-filestyle.min.js"></script>
         <!-- Animo-->
         <script src="<?php echo base_url() ?>vendor/animo.js/animo.min.js"></script>
         <!-- Sparklines-->
         <script src="<?php echo base_url() ?>vendor/sparkline/index.js"></script>
         <!-- Slimscroll-->
         <script src="<?php echo base_url() ?>vendor/slimScroll/jquery.slimscroll.min.js"></script>
         <!-- Store + JSON-->
         <script src="<?php echo base_url() ?>vendor/store-js/store%2bjson2.min.js"></script>
         <!-- ScreenFull-->
         <script src="<?php echo base_url() ?>vendor/screenfull/dist/screenfull.min.js"></script>
         <!-- START Page Custom Script-->
         <!-- END Page Custom Script-->
         <!-- App Main-->
         <script src="<?php echo base_url() ?>app/js/app.js"></script>
         <!-- END Scripts-->
          <script type="text/javascript">
              var selectedNode, mainReady = true;
          </script>
         <script type="text/javascript">
         $(document).ajaxStop($.unblockUI);
         $('#tMn').tree({
             url : '<?php echo base_url(); ?>api/menu',
             method: 'get',
             onSelect: function(node){


                 var tm = $('#tMn');
                  if (selectedNode == node.id && tm.tree('isLeaf',node.target)) {
                      return;
                  };

                  selectedNode = node.id;
                  if (mainReady === false) {
                      return;
                  };
                  if (tm.tree('isLeaf',node.target)) {
                    mainReady = false;
                    $('#content-block').panel('setTitle', node.judul );

                    $.get('<?php echo base_url(); ?>' + node.link)
                      .done(function( data ) {
                            $("#content-block").empty();
                            $("#content-block").append(data);
                            //
                        })
                        .fail(function(e) {
          
                        });
                    mainReady = true;
                  } else {
                    tm.tree('toggle',node.target)
                  }
             }
         });
         </script>
      </body>

      </html>