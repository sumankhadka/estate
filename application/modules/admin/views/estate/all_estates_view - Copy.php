<?php 
$curr_page = $this->uri->segment(5);
if($curr_page=='')
  $curr_page = 0;
$dl = default_lang();
?>
<div class="row">

  <div class="col-md-12">
    <div class="box">
      <div class="box-title">
        <h3><i class="fa fa-filter"></i> <?php echo lang_key('advanced_filters'); ?></h3>
        <div class="box-tool">
          <a href="#" data-action="collapse"><i class="fa fa-chevron-up"></i></a>
        </div>
      </div>

      <div class="box-content">
        <form action="" method="post" id="filter_form">
          <div class="col-md-3">
            <label>Purpose: </label>
            <?php $purposes = array("DBC_PURPOSE_SALE", "DBC_PURPOSE_RENT", "DBC_PURPOSE_BOTH");?>
            <select id="purpose-select" name="filter_purpose" class="form-control input-sm filters">
                    <option value=""><?php echo lang_key('all');?></option>
                  <?php foreach ($purposes as $purpose) { 
                      $sel = ($purpose==$this->session->userdata('filter_purpose'))?'selected="selected"':'';
                  ?>
                    <option value="<?php echo $purpose;?>" <?php echo $sel;?>><?php echo lang_key($purpose);?></option>
                <?php } ?>
            </select>
          </div>
          <div class="col-md-3">
            <label>Type: </label>
            <?php $types = array();
                  $this->load->config('realcon');
                  $custom_types = $this->config->item('property_types');
                  if(is_array($custom_types)) foreach ($custom_types as $key => $custom_type) {
                    $types[] = $custom_type['title'];
                  }
            ?>
            <select id="" name="filter_type" class="form-control input-sm filters">
                <option value=""><?php echo lang_key('all');?></option>
                <?php foreach ($types as $type) { 
                        $sel = ($type==$this->session->userdata('filter_type'))?'selected="selected"':'';
                    ?>
                <option value="<?php echo $type; ?>" <?php echo $sel;?>><?php echo lang_key($type);?></option>
                <?php } ?>
            </select>
          </div>
          <div class="col-md-3">
            <label>Condition: </label>
            <?php $conditions = array("DBC_CONDITION_NEW", "DBC_CONDITION_AVAILABLE", "DBC_CONDITION_SOLD", "DBC_CONDITION_AUCTION");?>
            <select name="filter_condition" class="form-control input-sm filters">
                  <option value=""><?php echo lang_key('all');?></option>
                  <?php foreach ($conditions as $status) { 
                        $sel = ($status==$this->session->userdata('filter_condition'))?'selected="selected"':'';
                    ?>
                    <option value="<?php echo $status;?>" <?php echo $sel;?>><?php echo lang_key($status);?></option>
                <?php } ?>
            </select>
          </div>
          <div class="col-md-3">
            <label>Status: </label>
            <?php $types = array("DBC_DELETED","DBC_ACTIVE","DBC_PENDING","DBC_REPORTED");?>
            <select id="" name="filter_status" class="form-control input-sm filters">
                <option value=""><?php echo lang_key('all');?></option>
                <?php foreach ($types as $key=>$type) { 
                        $sel = ($this->session->userdata('filter_status')!='' && $key==$this->session->userdata('filter_status'))?'selected="selected"':'';
                    ?>
                <option value="<?php echo $key; ?>" <?php echo $sel;?>><?php echo lang_key($type);?></option>
                <?php } ?>
            </select>
          </div>

          <div class="col-md-3">
            <label>Orderby: </label>
            <?php $orderby = array("home_size"=>"Home Size","lot_size"=>"Lot Size","total_price"=>"Price");?>
            <select name="filter_orderby" class="form-control input-sm filters">
                    <option value=""><?php echo lang_key('none');?></option>
                    <?php foreach ($orderby as $key=>$order) { 
                        $sel = ($key==$this->session->userdata('filter_orderby'))?'selected="selected"':'';
                    ?>
                    <option value="<?php echo $key;?>" <?php echo $sel;?>><?php echo lang_key($order);?></option>
                <?php } ?>
            </select>
          </div>
          <div class="col-md-3">
            <label>Orderby: </label>
            <?php $ordertype = array("ASC", "DESC");?>
            <select name="filter_ordertype" class="form-control input-sm filters">
                <?php foreach ($ordertype as $type) { 
                        $sel = ($type==$this->session->userdata('filter_ordertype'))?'selected="selected"':'';
                    ?>
                    <option value="<?php echo $type;?>" <?php echo $sel;?>><?php echo lang_key($type);?></option>
                <?php } ?>
            </select>
          </div>
        </form>
        <div class="clearfix" style="height:110px;"></div>  
      </div>

    </div>
  </div>    


  <div class="col-md-12">

    <div class="box">

      <div class="box-title">

        <h3><i class="fa fa-bars"></i> <?php echo lang_key('all_estates');?></h3>

        <div class="box-tool">

          <a href="#" data-action="collapse"><i class="fa fa-chevron-up"></i></a>


        </div>

      </div>

      <div class="box-content">

        <?php echo $this->session->flashdata('msg');?>

        <?php if($posts->num_rows()<=0){?>

        <div class="alert alert-info"><?php echo lang_key('no_estates_found');?></div>

        <?php }else{?>
        <form action="" method="post">
          <div class="input-group" style="float:left;width:350px;margin-bottom:20px;">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                <input type="text" name="id_search" class="form-control" placeholder="#Id">
                <span class="input-group-btn">
                <button type="submit" class="btn" style="border-radius:0 5px 5px 0">Search!</button>
                </span>
          </div>
        </form>
        <div id="no-more-tables">

        <table class="table table-hover">

           <thead>

               <tr>

                  <th class="numeric">#</th>

                  <th class="numeric"><?php echo lang_key('image');?></th>

                  <th class="numeric"><?php echo lang_key('title');?></th>

                  <th class="numeric"><?php echo lang_key('type');?></th>

                  <th class="numeric"><?php echo lang_key('purpose');?></th>

                  <th class="numeric"><?php echo lang_key('condition');?></th>
                  
                  <th class="numeric"><?php echo lang_key('price');?></th>

                  <th class="numeric"><?php echo lang_key('status');?></th>

                  <th class="numeric">Featured</th>

                  <th class="numeric"><?php echo lang_key('actions');?></th>

               </tr>

           </thead>

           <tbody>

        	<?php $i=$start+1;foreach($posts->result() as $row):  ?>

               <tr>

                  <td data-title="#" class="numeric"><?php echo $row->id;?></td>

                  <td data-title="Thumb" class="numeric"><img class="thumbnail" style="width:50px;margin-bottom:0px;" src="<?php echo get_featured_photo_by_id($row->featured_img);?>" /></td>

                  <td data-title="Title" class="numeric"><?php echo get_title_for_edit_by_id_lang($row->id,$dl);?></td>

                  <td data-title="Type" class="numeric"><?php echo lang_key($row->type);?></td>

                  <td data-title="Purpose" class="numeric"><?php echo lang_key($row->purpose)?></td>

                  <td data-title="Condition" class="numeric"><?php echo lang_key($row->estate_condition)?></td>
                  
                  <td data-title="Price" class="numeric"><?php echo $row->total_price;?></td>
                  
                  <td data-title="<?php echo lang_key('status');?>" class="numeric"><?php echo get_status_title_by_value($row->status);?></td>
                  
                  <td data-title="Featured" class="numeric"><?php echo ($row->featured==1)?'<span class="label label-success">Yes</span>':'<span class="label label-info">No</span>';?></td>

                  <td data-title="<?php echo lang_key('actions');?>" class="numeric">

                    <div class="btn-group">

                      <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-cog"></i> <?php echo lang_key('action');?> <span class="caret"></span></a>

                      <ul class="dropdown-menu dropdown-info">

                          <li><a href="<?php echo site_url('admin/realestate/editestate/'.$curr_page.'/'.$row->id);?>"><?php echo lang_key('edit');?></a></li>
                          <li><a href="<?php echo site_url('admin/realestate/deleteestate/'.$curr_page.'/'.$row->id);?>"><?php echo lang_key('delete');?></a></li>
                          <?php if(is_admin()){?>
                            <?php if($row->status==2){?>
                            <li><a href="<?php echo site_url('admin/realestate/approveestate/'.$curr_page.'/'.$row->id);?>">Approve</a></li>
                            <?php }?>
                            <?php if($row->featured==0){?>
                            <li><a href="<?php echo site_url('admin/realestate/featurepost/'.$curr_page.'/'.$row->id);?>">Make Featured</a></li>
                            <?php }else{?>
                            <li><a href="<?php echo site_url('admin/realestate/removefeaturepost/'.$curr_page.'/'.$row->id);?>">Remove Featured</a></li>
                            <?php }?>
                          <?php }else{?>
                            <?php if(get_settings('realestate_settings','enable_feature_payment','No')=='Yes' && $row->featured==0){?>
                            <li><a href="<?php echo site_url('admin/realestate/featurepayment/'.$curr_page.'/'.$row->id);?>">Make Featured</a></li>
                            <?php }?>
                          <?php }?>
                      </ul>

                    </div>

                  </td>

               </tr>

            <?php $i++;endforeach;?>   

           </tbody>

        </table>

        </div>

        <div class="pagination"><ul class="pagination pagination-colory"><?php echo $pages;?></ul></div>

        <?php }?>

        </div>

    </div>

  </div>

</div>


<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('.filters').change(function(){
        jQuery('#filter_form').submit();
    });
});
</script>
