<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/assets/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/css/dbcadmin.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/css/memento-responsive.css">

<!--Table-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/assets/data-tables/bootstrap3/dataTables.bootstrap.css" />

<!--Gritter-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/assets/gritter/css/jquery.gritter.css">

<!--Calendar-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/assets/jquery-ui/jquery-ui.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/assets/fullcalendar/fullcalendar/fullcalendar.css"/>

<!--Rickh Text Editor-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />

<!--Rickh Text Editor-->

<link href="<?php echo base_url();?>assets/admin/css/no-more-table.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/css/custom-admin.css" rel="stylesheet">

<?php echo $this->session->flashdata('msg');?>
<h4>New <?php echo $type;?></h4>
<hr/>

<form action="<?php echo site_url('admin/realestate/savetrainline');?>" method="post" id="save-trainline-form">

<input type="hidden" name="type" value="<?php echo $type;?>" />	

<?php if($type=='station' || $type=="city"){?>
	<label>Select Trainline</label>
	<select name="trainline" class="form-control trainline-<?php echo $type;?>">
		<option value=""> Select a trainline</option>
		<?php 
		foreach ($trainlines->result() as $row) {
			$sel = (set_value('trainline')==$row->id)?'selected="selected"':'';
			echo '<option value="'.$row->id.'" '.$sel.'>'.$row->name.'</option>';
		}
		?>
	</select>	
<?php echo form_error('trainline');?>
<?php }?>


<label><?php echo $type;?> names :</label>
<textarea class="form-control" style="height:260px;" name="locations" ></textarea>
<div class="alert alert-info">Put one or more <?php echo $type;?> name as "," (comma) separated. Like Newyork,Dallas,idaho</div>
<?php echo form_error('locations');?>

<div class="clearfix"></div>
<input type="submit" value="Save" class="btn btn-success" style="margin-top:10px;" >
</form>


<script type="text/javascript">
	jQuery('#save-trainline-form').submit(function(event){
		event.preventDefault();
		var loadUrl = jQuery(this).attr('action');
		jQuery("#trainline-model  .modal-body").html("Updating...");
		jQuery.post(
			loadUrl,
			jQuery(this).serialize(),
			function(responseText){
				jQuery("#trainline-model  .modal-body").html(responseText);
			},
			"html"
		);

	});

	
</script>	