<div class="main-content" >
<div id="breadcrumbs" class="breadcrumbs">
 <script type="text/javascript">
  try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
 </script>
 <ul class="breadcrumb">
  <li>
   <i class="icon-home home-icon"></i>
   <a href="#">Home</a>
   <span class="divider">
    <i class="icon-angle-right arrow-icon"></i>
   </span>
  </li>
  <li class="active"><?php echo lang('reports');?></li>
 </ul>
</div>

<div class="page-header position-relative">
                <h1>
                    <?php echo lang("curriculum");?>
                    <small>
                        <i class="icon-double-angle-right"></i>
                        Mòduls professionals / Crèdits
                    </small>
                </h1>
</div><!-- /.page-header -->

<div style='height:10px;'></div>
	<div style="margin:10px;">
   		



      <script>
      $(function(){

              $('#all_groups').dataTable( {
                      "aLengthMenu": [[10, 25, 50,100,200,-1], [10, 25, 50,100,200, "<?php echo lang('All');?>"]],
                              "oTableTools": {
                  "sSwfPath": "<?php echo base_url('assets/grocery_crud/themes/datatables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf');?>",
                              "aButtons": [
                                      {
                                              "sExtends": "copy",
                                              "sButtonText": "<?php echo lang("Copy");?>"
                                      },
                                      {
                                              "sExtends": "csv",
                                              "sButtonText": "CSV"
                                      },
                                      {
                                              "sExtends": "xls",
                                              "sButtonText": "XLS"
                                      },
                                      {
                                              "sExtends": "pdf",
                                              "sPdfOrientation": "landscape",
                                              "sPdfMessage": "<?php echo lang("all_groups");?>",
                                              "sTitle": "TODO",
                                              "sButtonText": "PDF"
                                      },
                                      {
                                              "sExtends": "print",
                                              "sButtonText": "<?php echo lang("Print");?>"
                                      },
                              ]

              },
              "iDisplayLength": 100,
                "oLanguage": {
                        "sProcessing":   "Processant...",
                        "sLengthMenu":   "Mostra _MENU_ registres",
                        "sZeroRecords":  "No s'han trobat registres.",
                        "sInfo":         "Mostrant de _START_ a _END_ de _TOTAL_ registres",
                        "sInfoEmpty":    "Mostrant de 0 a 0 de 0 registres",
                        "sInfoFiltered": "(filtrat de _MAX_ total registres)",
                        "sInfoPostFix":  "",
                        "sSearch":       "Filtrar:",
                        "sUrl":          "",
                        "oPaginate": {
                                "sFirst":    "Primer",
                                "sPrevious": "Anterior",
                                "sNext":     "Següent", 
                                "sLast":     "Últim"    
                        }
            }
             
        });  

});
</script>

<div class="container">

<table class="table table-striped table-bordered table-hover table-condensed" id="all_groups">
 <thead style="background-color: #d9edf7;">
  <tr>
    <td colspan="13" style="text-align: center;"> <h4>
      <a href="<?php echo base_url('/index.php/curriculum/study_modules') ;?>">
        <?php echo $study_modules_table_title?>
      </a>
      </h4></td>
  </tr>
  <tr> 
     <th><?php echo lang('study_module_id')?></th>
     <th><?php echo lang('study_module_code')?></th>
     <th><?php echo lang('study_module_shortname')?></th>
     <th><?php echo lang('study_module_name')?></th>
     <th><?php echo lang('study_module_course')?></th>
     <th><?php echo lang('study_module_study')?></th>
     <th><?php echo lang('study_module_hoursPerWeek')?></th>
     <th><?php echo lang('study_module_order')?></th>
     <th><?php echo lang('study_module_initialDate')?></th>
     <th><?php echo lang('study_module_endDate')?></th>
     <th><?php echo lang('study_module_type')?></th>
     <th><?php echo lang('study_module_subtype')?></th>
  </tr>
 </thead>
 <tbody> 

  <!-- Iteration that shows study_modules-->
  <?php foreach ($all_studymodules as $study_module_key => $study_module) : ?>
   <tr align="center" class="{cycle values='tr0,tr1'}">   
     <td>
      <a href="<?php echo base_url('/index.php/curriculum/study_module/edit/' . $study_module->id ) ;?>">
          <?php echo $study_module->id;?>
      </a> 
     </td>
     <td>
      <a href="<?php echo base_url('/index.php/curriculum/study_module/read/' . $study_module->id ) ;?>">
          <?php echo $study_module->code;?>
      </a> 
     </td>
     <td>
      <a href="<?php echo base_url('/index.php/curriculum/study_module/read/' . $study_module->id ) ;?>">
          <?php echo $study_module->shortname;?>
      </a> 
     </td>
     <td>
      <a href="<?php echo base_url('/index.php/curriculum/study_module/read/' . $study_module->id ) ;?>">
          <?php echo $study_module->name;?>
      </a> 
     </td>
     <td>
      <a href="<?php echo base_url('/index.php/curriculum/course/read/' . $study_module->course_id ) ;?>">
          <?php echo $study_module->course_shortname . ". " . $study_module->course_name;?>
      </a>
      ( <a href="<?php echo base_url('/index.php/curriculum/course/edit/' . $study_module->course_id ) ;?>">
          <?php echo $study_module->course_id ;?>
      </a> )
     </td>
     
     <td>
      <a href="<?php echo base_url('/index.php/curriculum/studies/read/' . $study_module->study_id ) ;?>">
          <?php echo $study_module->study_shortname . ". " . $study_module->study_name . " - " . $study_module->study_law_name . " -" . $study_module->study_law_shortname;?>
      </a>
      ( <a href="<?php echo base_url('/index.php/curriculum/studies/edit/' . $study_module->course_id ) ;?>">
          <?php echo $study_module->course_id ;?>
      </a> )
     </td>

     <td>
      <?php echo $study_module->study_module_hoursPerWeek;?>
    </td>
    
    <td>
      <?php echo $study_module->study_module_order;?>
    </td>

    <td>
      <?php echo $study_module->study_module_initialDate;?>
    </td>

    <td>
      <?php echo $study_module->study_module_endDate;?>
    </td>

    <td>
      <?php echo $study_module->study_module_type;?>
    </td>


    <td>
      <?php echo $study_module->study_module_subtype;?>
    </td>



   </tr>
  <?php endforeach; ?>
 </tbody>
</table> 

</div>

<div class="space-30"></div>

	</div>	
</div>