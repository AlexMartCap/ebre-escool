<script>
$(function(){
	
	$("[rel='tooltip']").tooltipster();	
	
	$(".chosen-select,.chosen-multiple-select").chosen({allow_single_deselect:true});	
	
	$('#group_code').chosen().change(function(event) {
		selectedValue = $(this).find("option:selected").val();
		window.location.href = "<?php echo base_url('index.php/managment/massive_change_password');?>/" + selectedValue;
	}); 
	
	
	$('#massive_password_change_form').submit(function(e) {
		var r=confirm("<?php echo lang("are_you_sure_change_and_print_passwords"); ?>? <br/><?php echo lang("are_you_sure_change_and_print_passwords2");?>.");
		if (r==true)	{
			window.open('', 'formpopup', 'width=200,height=200,scrollbars=yes');
			this.target = 'formpopup';
		} else {
			e.preventDefault();
		}
    });
    
    $('#all_users_in_group').dataTable( {
		"sDom": 'T<"clear">lfrtip',
		"aLengthMenu": [[10, 25, 50,100,200,500,1000,-1], [10, 25, 50,100,200,500,1000, "All"]],
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
					"sPdfMessage": "<?php echo lang("students_group");?>",
					"sTitle": "<?php echo $selected_group . ". " . $selected_group_names[1] . "( " . $selected_group_names[0] . ")" ;?>",
					"sButtonText": "PDF"
				},
				{
					"sExtends": "print",
					"sButtonText": "<?php echo lang("Print");?>"
				},
			]

        },
        "iDisplayLength": 50,
        "aaSorting": [[ 2, "asc" ],[ 3, "asc" ],[ 4, "asc" ]],
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
	

<div class="row">
 <div class="span12"> </div>
</div>

<?php
$datasources=array();
$datasources['mysql']="MySQL";
$datasources['ldap']="Ldap";
$selected_datasource="ldap";
$data_source_additional_parameters="";
?>

<div class="row" style="display:none">
  <div class="span4"> </div>
  <div class="span4" style="padding:5px;">
   <?php echo lang("select_data_source")?> : 
  </div>
</div>  

<form id="massive_password_change_form" action="<?php echo base_url('index.php/managment/massive_change_password_print');?>" method="post">

<div class="row" style="display:none">
  <div class="span4"> </div>
  <div class="span4" style="padding:5px;">
    <select class="chosen-select" data-place_holder="<?php echo lang("select_data_source")?>">
	 <?php foreach ($datasources as $data_source_key => $data_source_value): ?>
		<?php $group_additional_parameters=""; ?>
		<?php if ($data_source_key == $selected_datasource)	{
			$data_source_additional_parameters='selected="selected"';
		}
		?>		
		<option value="<?php echo $data_source_key?>" <?php echo $data_source_additional_parameters?>$><?php echo $data_source_value?></option>
	 <?php endforeach; ?>
    </select>
  </div>

</div>

<div class="row">
  <div class="span4"> </div>
  <div class="span4" style="padding:5px;">
   <?php echo lang("select_group")?> : 
  </div>
</div>  

<div class="row" >
  <div class="span4"> </div>
  <div class="span4" style="padding:5px;">
   <select id="group_code" name="group_code" class="chosen-select" data-place_holder="<?php echo lang("select_group")?>" style="width:400px;">
    <option value="ALL_GROUPS"><?php echo lang("all_groups");?></option>
	<?php foreach ($all_groups as $group_key => $group) : ?>
	    <?php $group_additional_parameters=""; ?>
		<?php if ($group->groupCode == $selected_group)	{
			$group_additional_parameters='selected="selected"';
		}?>
		<option value="<?php echo $group->groupCode;?>" <?php echo $group_additional_parameters?>><?php echo $group->groupCode . " - " . $group->groupShortName?></option>

	 <?php endforeach; ?>
   </select>
   <select name="only_students_with_all_data" class="chosen-select" data-place_holder="TODO" style="width:400px;">
		 <option value="1" selected="selected"><?php echo lang("print_only_students_with_all_data");?></option>
		 <option value="2"><?php echo lang("print_only_students_with_email");?></option>
		 <option value="3"><?php echo lang("print_only_students_with_photo");?></option>
		 <option value="4"><?php echo lang("print_only_students");?></option>
   </select>
   <button id="print_new_passwords" class="btn" type="submit"><?php echo lang("change_and_print_passwords");?></button>
  </div>
</div>
</form>

<?php
$colspan_value=9;
if ($selected_group == "ALL_GROUPS")	{
	$colspan_value=10;
}
	  
?>

<table class="table table-striped table-bordered table-hover table-condensed" id="all_users_in_group">
 <thead style="background-color: #d9edf7;">
  <tr>
    <td colspan="<?php echo $colspan_value;?>" style="text-align: center;"> <h4><?php echo lang('all_students_table_title') . ". " . lang('group') . ": " . $selected_group_names[1] . " (" . $selected_group_names[0] . ")"?></h4></td>
  </tr>
  <tr>
	 <?php if ($selected_group == "ALL_GROUPS"): ?>
	  <th><font size="-4"><?php echo lang('group')?></font></th>	
	 <?php endif;?>
     <th><font size="-4"><?php echo lang('externalID')?></font></th>
     <th><font size="-4"><?php echo lang('internalID')?></font></th>
     <th><font size="-4"><?php echo lang('sn1')?></font></th>
     <th><font size="-4"><?php echo lang('sn2')?></font></th>
     <th><font size="-4"><?php echo lang('givenName')?></font></th>
     <th><font size="-4"><?php echo lang('uid')?></font></th>
     <th><font size="-4"><?php echo lang('personal_email')?></font></th>
     <th><font size="-4"><?php echo lang('photo')?></font></th>
     <th><font size="-4"><?php echo lang('Ok')?></font></th>
  </tr>
 </thead>
 <tbody>
  <!-- Iteration that shows teacher groups for selected day-->
  <?php foreach ($all_students_in_group as $student_key => $student) : ?>
   <tr align="center" class="{cycle values='tr0,tr1'}">
	 <?php if ($selected_group == "ALL_GROUPS"): ?>
	  <td><font size="-4">
	  <?php
	   echo $student->group_code . " ( " . str_replace(" ","_",$student->group_code) . " )";
	  ?>
	  
	  </font></td>
	 <?php endif;?>
     <td rel='tooltip' title="<?php echo $student->dn;?>"><font size="-4"><?php echo $student->irisPersonalUniqueID;?></font></td>
     <td rel='tooltip' title="<?php echo $student->dn;?>"><font size="-4"><?php echo $student->highSchoolUserId;?></font></td>
     <td rel='tooltip' title="<?php echo $student->dn;?>"><font size="-4"><?php echo ucfirst(strtolower($student->sn1));?></font></td>
     <td rel='tooltip' title="<?php echo $student->dn;?>"><font size="-4"><?php echo ucfirst(strtolower($student->sn2));?></font></td>
     <td rel='tooltip' title="<?php echo $student->dn;?>"><font size="-4"><?php echo ucfirst(strtolower($student->givenName));?></font></td>
     <td rel='tooltip' title="<?php echo $student->dn;?>"><font size="-4"><?php echo $student->uid;?></font></td>
     <td rel='tooltip' title="<?php echo $student->dn;?>"><font size="-4"><?php echo $student->highSchoolPersonalEmail;?></font></td>
     <td rel='tooltip' title="<?php echo $student->dn;?>">
	  <font size="-4">
	   <?php if ($student->jpegPhoto !="") {
				echo "S";
		   }
		   else {
				echo "N";
		   }?>
	  </font>	   
	 </td>
	 <td rel='tooltip' title="<?php echo $student->dn;?>">
	  <font size="-4">
	   
	   <?php if (($student->jpegPhoto !="") && ($student->highSchoolPersonalEmail !="")) {
				echo "S";
		   }
		   else {
				echo "N";
		   }?>
	  </font>
	 </td>
   </tr>
  <?php endforeach; ?>
 </tbody>
</table>


</div>
