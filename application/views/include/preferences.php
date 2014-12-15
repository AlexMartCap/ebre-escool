<div class="main-content">
 
 <div id="breadcrumbs" class="breadcrumbs">
  <script type="text/javascript">
   try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
  </script>
  <ul class="breadcrumb">
   <li>
    <i class="icon-home home-icon"></i>
    <a href="<?php echo base_url();?>">Home</a>
    <span class="divider">
     <i class="icon-angle-right arrow-icon"></i>
    </span>
   </li>
   <li class="active"><a href="<?php echo base_url('/index.php/skeleton/preferences');?>"><?php echo lang('preferences');?></a></li>
  </ul>
 </div>

 <div class="page-content">

  <div class="page-header position-relative">
                        <h1>
                            <a href="<?php echo base_url('index.php/skeleton/preferences');?>"><?php echo lang("preferences");?></a>
                        </h1>
  </div>

  <?php
  
  if (isset($message)) {
    if ($message == 1) {
      $this->load->view($this->user_preferences_NOT_yet_header_view);                
    }

    if ($message == 2) {
      $this->load->view($this->user_preferences_for_admin_header_view,$user_preferences_id);
    }  
  }
  
  ?>
  <!--Grocery Crud -->
  <?php echo $output; ?>
 </div>

</div>