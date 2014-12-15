<div id="skeleton_body_footer">

<style type="text/css">
.footer {
	color:#666; 
	background:#fff !important; 
	padding: 5px 0 5px 0;
	border-top:1px solid #ccc !important;
}
.footer a:link, .footer a:visited {
	color:#444 !important;
}
.footer a:hover {
	color:#666 !important; 
	border-bottom: 1px dotted #666 !important;
	text-decoration: none;
}

/*	  
    .footer {		   
		color: #666;
		background: #222;
		padding: 5px 0 5px 0;
		border-top: 1px solid #000;
     }
     
    .footer a {
		color: #999;
    }
    
    .footer a:hover {
         	color: #efefef;
    }	
*/

</style>
<div style="height: 35px;"></div>
<div style="clear:both"></div>
<div class="navbar navbar-fixed-bottom">
	<div class="navbar-inner footer">
		<div class="container">    
		
		<a href="<?php echo $body_footer_entity_url;?>">
		 <img alt="<?php echo $body_footer_entity_name;?>" src="<?php echo $body_footer_entity_image_url?>">
        </a>
		© <a href="<?php echo $body_footer_entity_url;?>" rel="author"><?php echo $body_footer_entity_name;?></a> <?php echo $body_footer_copyright_date;?> – <?php echo $body_footer_authors;?>, 
		<a href="<?php echo $body_footer_entity_url;?>" rel="author"> <?php echo $body_footer_entity_url_name;?></a> | <a href="<?php echo $body_footer_wiki_url;?>"> Wiki</a> | <a href="<?php echo $body_footer_github_url;?>"> Github</a>
		
		</div>	
	</div>
</div>

<div class="ace-settings-container" id="ace-settings-container">
	<div class="btn btn-app btn-mini btn-warning ace-settings-btn" id="ace-settings-btn">
		<i class="icon-cog bigger-150"></i>
	</div>

	<div class="ace-settings-box" id="ace-settings-box">
		<div>
			<div class="pull-left">
				<select id="skin-colorpicker" class="hide">
					<option data-skin="default" value="#438EB9">#438EB9</option>
					<option data-skin="skin-1" value="#222A2D">#222A2D</option>
					<option data-skin="skin-2" value="#C6487E">#C6487E</option>
					<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
				</select>
			</div>
			<span>&nbsp; Escolliu Skin</span>
		</div>

		<div>
			<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
			<label class="lbl" for="ace-settings-navbar"> Menú superior fixe</label>
		</div>

		<div>
			<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
			<label class="lbl" for="ace-settings-sidebar"> Menú lateral fixe</label>
		</div>

		<div>
			<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
			<label class="lbl" for="ace-settings-breadcrumbs"> Molletes fixes</label>
		</div>

		<div>
			<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
			<label class="lbl" for="ace-settings-rtl"> Dreta a esquerra</label>
		</div>
	</div>
</div><!-- /#ace-settings-container -->
	
</div>


