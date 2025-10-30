<?php
namespace SIM\FANCYEMAIL;
use SIM;

const MODULE_VERSION		= '8.0.9';

DEFINE(__NAMESPACE__.'\MODULE_PATH', plugin_dir_path(__DIR__));

//module slug is the same as grandparent folder name
DEFINE(__NAMESPACE__.'\MODULE_SLUG', strtolower(basename(dirname(__DIR__))));

//run on module activation
add_action('sim_module_fancyemail_activated', __NAMESPACE__.'\moduleActivated');
function moduleActivated(){
	// Create the dbs
	$fancyEmail     = new FancyEmail();
	$fancyEmail->createDbTables();
}

add_filter('sim_submenu_fancyemail_options', __NAMESPACE__.'\subMenuOptions', 10, 2);
function subMenuOptions($optionsHtml, $settings){
	ob_start();
	
    ?>
	<label>
		<input type='checkbox' name='no-statistics' value='1' <?php if(isset($settings['no-statistics'])){echo 'checked';}?>>
		Do not keep statistics about e-mails
	</label>
	<br><br>
	<label>
		Default e-mail greeting<br>
		<input type='text' name='closing' value='<?php if(isset($settings['closing'])){echo $settings['closing'];}else{echo 'Kind regards'; }?>'>
	</label>
	<br><br>
	<label>
		<input type='checkbox' name='no-staging' value='1' <?php if(isset($settings['no-staging'])){echo 'checked';}?>>
		Do not send e-mails from staging websites
	</label>
	<br>
	<label>
		<input type='checkbox' name='no-localhost' value='1' <?php if(isset($settings['no-localhost'])){echo 'checked';}?>>
		Do not send e-mails from localhost
	</label>
	<br>
	<br>
	<label>
		Max attachment size in MB (multiple e-mails will be send to stay below the maximum if needed)<br>
		<input type='number' name='maxsize' value='<?php if(isset($settings['maxsize'])){echo $settings['maxsize'];}?>'>
	</label>
	<br>
	<br>
	<label>Select a picture for the e-mail header.</label>
	<?php
	SIM\pictureSelector('header_image', 'e-mail header', $settings);

	return $optionsHtml.ob_get_clean();
}
	
add_filter('sim_module_fancyemail_data', __NAMESPACE__.'\moduleData');
function moduleData($dataHtml){
	return $dataHtml.emailStats();
}