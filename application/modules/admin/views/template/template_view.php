<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>estatezoon | <?php echo (isset($title))?$title:'Admin panel';?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'includes_top.php';?>
</head>

<?php 
$style = '';
$CI = get_instance();
$curr_lang  = $CI->uri->segment(1);
if($curr_lang=='' || $curr_lang=='admin')
    $curr_lang = default_lang();

if($curr_lang=='ar' || $curr_lang=='fa' || $curr_lang=='he' || $curr_lang=='ur')
{
    ?>
    <link href="<?php echo base_url();?>assets/admin/css/rtl-fix.css" rel="stylesheet">
    <?php
    $style = '';
?>
<body dir="rtl">
<?php 
}else{
?>
<body>
<?php 
}
?>
	<?php //include 'theme_settings.php';?>
    <?php include 'header.php';?>
    <div class="container" id="main-container">
        <?php include 'navigation.php';?>
        <div id="main-content" style="<?php echo $style;?>">
            <div id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo site_url('admin');?>"><?php echo (is_admin())?'Admin Panel':'Agent Panel';?></a>
                    <span class="divider"><i class="fa fa-angle-right"></i></span>
                    </li>
                    <li class="active"><?php echo (isset($title))?$title:'';?></li>
                </ul>
            </div>

        	<?php if(isset($content))echo $content;?>
           <?php include 'footer.php';?>
        </div>
    </div>
    <?php include 'includes_bottom.php';?>
</body>
</html>