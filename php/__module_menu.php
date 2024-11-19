<?php
namespace SIM\FANCYEMAIL;
use SIM;

const MODULE_VERSION		= '8.0.4';

DEFINE(__NAMESPACE__.'\MODULE_PATH', plugin_dir_path(__DIR__));

//module slug is the same as grandparent folder name
DEFINE(__NAMESPACE__.'\MODULE_SLUG', strtolower(basename(dirname(__DIR__))));

//run on module activation
add_action('sim_module_activated', __NAMESPACE__.'\moduleActivated');
function moduleActivated($moduleSlug){
	//module slug should be the same as grandparent folder name
	if($moduleSlug != MODULE_SLUG)	{
		return;
	}

	// Create the dbs
	$fancyEmail     = new FancyEmail();
	$fancyEmail->createDbTables();
}

add_filter('sim_submenu_options', __NAMESPACE__.'\subMenuOptions', 10, 3);
function subMenuOptions($optionsHtml, $moduleSlug, $settings){
	//module slug should be the same as grandparent folder name
	if($moduleSlug != MODULE_SLUG){
		return $optionsHtml;
	}

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

	return ob_get_clean();
}
	
add_filter('sim_module_data', __NAMESPACE__.'\moduleData', 10, 3);
function moduleData($dataHtml, $moduleSlug, $settings){
	//module slug should be the same as grandparent folder name
	if($moduleSlug != MODULE_SLUG || SIM\getModuleOption(MODULE_SLUG, 'no-statistics')){
		return $dataHtml;
	}

	return $dataHtml.emailStats();
}