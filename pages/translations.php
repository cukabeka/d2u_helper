<?php
// Set Session
if(!isset($_SESSION['d2u_helper_translation'])) {
	$_SESSION['d2u_helper_translation'] = [];
	$_SESSION['d2u_helper_translation']['clang_id'] = rex_clang::getStartId();
	$_SESSION['d2u_helper_translation']['filter'] = 'update';
}

// Save form in session
if (filter_input(INPUT_POST, "btn_save") == 'save') {
	$settings = (array) rex_post('settings', 'array', []);
	$_SESSION['d2u_helper_translation']['clang_id'] =  $settings['clang_id'];
	$_SESSION['d2u_helper_translation']['filter'] = $settings['filter'];
}
?>

<h2><?php print rex_i18n::msg('d2u_helper_meta_translations'); ?></h2>
<p><?php echo rex_i18n::msg('d2u_helper_translations_description'); ?></p>

<?php
if(count(rex_clang::getAll()) == 1) {
	echo rex_view::warning(rex_i18n::msg('d2u_helper_translations_none'));
}
else {
?>
	<form action="<?php print rex_url::currentBackendPage(); ?>" method="post">
		<div class="panel panel-edit">
			<header class="panel-heading"><div class="panel-title"><?php print rex_i18n::msg('d2u_helper_translations_filter'); ?></div></header>
			<div class="panel-body">
				<?php
					// Language selection
					if(count(rex_clang::getAll()) > 1) {
						$lang_options = [];
						foreach(rex_clang::getAll() as $rex_clang) {
							if(rex_config::get('d2u_helper', 'default_lang') != $rex_clang->getId() &&
								(\rex::getUser()->isAdmin() || \rex::getUser()->getComplexPerm('clang')->hasPerm($rex_clang->getId()))) {
								$lang_options[$rex_clang->getId()] = $rex_clang->getName();
							}
						}
					}
					d2u_addon_backend_helper::form_select('d2u_helper_translations_language', 'settings[clang_id]', $lang_options, [$_SESSION['d2u_helper_translation']['clang_id']]);

					$filter_options = [
						"update" => rex_i18n::msg('d2u_helper_translations_filter_update'),
						"missing" => rex_i18n::msg('d2u_helper_translations_filter_missing')
					];
					d2u_addon_backend_helper::form_select('d2u_helper_translations_filter_select', 'settings[filter]', $filter_options, [$_SESSION['d2u_helper_translation']['filter']]);
				?>
			</div>
			<footer class="panel-footer">
				<div class="rex-form-panel-footer">
					<div class="btn-toolbar">
						<button class="btn btn-save rex-form-aligned" type="submit" name="btn_save" value="save"><?php echo rex_i18n::msg('d2u_helper_translations_apply'); ?></button>
					</div>
				</div>
			</footer>
		</div>
	</form>
<?php

	if(rex_addon::get('d2u_address')->isAvailable()) {
		$countries = D2U_Address\Country::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?php print rex_i18n::msg('d2u_address'); ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon fa-flag"></i></small> <?php echo rex_i18n::msg('d2u_address_countries'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($countries) > 0) {
						print '<ul>';
						foreach($countries as $country) {
							print '<li><a href="'. rex_url::backendPage('d2u_address/country', ['entry_id' => $country->country_id, 'func' => 'edit']) .'">'. $country->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
		</div>
	</div>
<?php
	}

	if(rex_addon::get('d2u_history')->isAvailable()) {
		$history_events = D2U_History\History::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?php print rex_i18n::msg('d2u_history'); ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon fa-flag"></i></small> <?php echo rex_i18n::msg('d2u_history_events'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($history_events) > 0) {
						print '<ul>';
						foreach($history_events as $history_event) {
							print '<li><a href="'. rex_url::backendPage('d2u_history/history', ['entry_id' => $history_event->history_id, 'func' => 'edit']) .'">'. $history_event->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
		</div>
	</div>
<?php
	}

	if(rex_addon::get('d2u_immo')->isAvailable()) {
		$categories = D2U_Immo\Category::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
		$properties = D2U_Immo\Property::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?php print rex_i18n::msg('d2u_immo'); ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-open-category"></i></small> <?php echo rex_i18n::msg('d2u_immo_categories'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($categories) > 0) {
						print '<ul>';
						foreach($categories as $category) {
							print '<li><a href="'. rex_url::backendPage('d2u_immo/category', ['entry_id' => $category->category_id, 'func' => 'edit']) .'">'. $category->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-home"></i></small> <?php echo rex_i18n::msg('d2u_immo_properties'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($properties) > 0) {
						print '<ul>';
						foreach($properties as $property) {
							print '<li><a href="'. rex_url::backendPage('d2u_immo/property', ['entry_id' => $property->property_id, 'func' => 'edit']) .'">'. $property->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<?php
				if(rex_plugin::get('d2u_immo', 'window_advertising')->isAvailable()) {
					$ads = D2U_Immo\Advertisement::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
			?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-desktop"></i></small> <?php echo rex_i18n::msg('d2u_immo_window_advertising') .' - '. rex_i18n::msg('d2u_immo_window_advertising_ads'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($ads) > 0) {
						print '<ul>';
						foreach($ads as $ad) {
							print '<li><a href="'. rex_url::backendPage('d2u_immo/window_advertising/advertisement', ['entry_id' => $ad->ad_id, 'func' => 'edit']) .'">'. $ad->title .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<?php
				}
			?>
		</div>
	</div>
<?php
	}

	if(rex_addon::get('d2u_jobs')->isAvailable()) {
		$categories = D2U_Jobs\Category::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
		$jobs = D2U_Jobs\Job::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?php print rex_i18n::msg('d2u_jobs'); ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-open-category"></i></small> <?php echo rex_i18n::msg('d2u_jobs_categories'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($categories) > 0) {
						print '<ul>';
						foreach($categories as $category) {
							print '<li><a href="'. rex_url::backendPage('d2u_jobs/category', ['entry_id' => $category->category_id, 'func' => 'edit']) .'">'. $category->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-users"></i></small> <?php echo rex_i18n::msg('d2u_jobs_jobs'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($jobs) > 0) {
						print '<ul>';
						foreach($jobs as $job) {
							print '<li><a href="'. rex_url::backendPage('d2u_jobs/jobs', ['entry_id' => $job->job_id, 'func' => 'edit']) .'">'. $job->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
		</div>
	</div>
<?php
	}

	if(rex_addon::get('d2u_linkbox')->isAvailable()) {
		$linkboxes = \D2U_Linkbox\Linkbox::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?php print rex_i18n::msg('d2u_linkbox'); ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon fa-window-maximize"></i></small> <?php echo rex_i18n::msg('d2u_linkbox_linkbox'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($linkboxes) > 0) {
						print '<ul>';
						foreach($linkboxes as $linkbox) {
							print '<li><a href="'. rex_url::backendPage('d2u_linkbox/linkbox', ['entry_id' => $linkbox->box_id, 'func' => 'edit']) .'">'. $linkbox->title .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
		</div>
	</div>
<?php
	}
	
		if(rex_addon::get('d2u_machinery')->isAvailable()) {
		$categories = Category::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
		$machines = Machine::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?php print rex_i18n::msg('d2u_machinery_meta_title'); ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-open-category"></i></small> <?php echo rex_i18n::msg('d2u_machinery_meta_categories'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($categories) > 0) {
						print '<ul>';
						foreach($categories as $category) {
							print '<li><a href="'. rex_url::backendPage('d2u_machinery/category', ['entry_id' => $category->category_id, 'func' => 'edit']) .'">'. $category->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-module"></i></small> <?php echo rex_i18n::msg('d2u_machinery_meta_machines'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($machines) > 0) {
						print '<ul>';
						foreach($machines as $machine) {
							print '<li><a href="'. rex_url::backendPage('d2u_machinery/machine', ['entry_id' => $machine->machine_id, 'func' => 'edit']) .'">'. $machine->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<?php
				if(rex_plugin::get('d2u_machinery', 'industry_sectors')->isAvailable()) {
					$industry_sectors = IndustrySector::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
			?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-industry"></i></small> <?php echo rex_i18n::msg('d2u_machinery_industry_sectors'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($industry_sectors) > 0) {
						print '<ul>';
						foreach($industry_sectors as $industry_sector) {
							print '<li><a href="'. rex_url::backendPage('d2u_machinery/industry_sectors', ['entry_id' => $industry_sector->industry_sector_id, 'func' => 'edit']) .'">'. $industry_sector->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<?php
				}
				if(rex_plugin::get('d2u_machinery', 'machine_certificates_extension')->isAvailable()) {
					$certificates = Certificate::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
			?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-certificate"></i></small> <?php echo rex_i18n::msg('d2u_machinery_certificates'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($certificates) > 0) {
						print '<ul>';
						foreach($certificates as $certificate) {
							print '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_certificates_extension', ['entry_id' => $certificate->certificate_id, 'func' => 'edit']) .'">'. $certificate->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<?php
				}
				if(rex_plugin::get('d2u_machinery', 'machine_features_extension')->isAvailable()) {
					$features = Feature::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
			?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-plug"></i></small> <?php echo rex_i18n::msg('d2u_machinery_features'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($features) > 0) {
						print '<ul>';
						foreach($features as $feature) {
							print '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_features_extension', ['entry_id' => $feature->feature_id, 'func' => 'edit']) .'">'. $feature->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<?php
				}
				if(rex_plugin::get('d2u_machinery', 'machine_steel_processing_extension')->isAvailable()) {
					$automations = Automation::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
					$materials = Material::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
					$procedures = Procedure::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
					$processes = Process::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
					$profiles = Profile::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
					$supplies = Supply::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
					$tools = Tool::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
					$weldings = Welding::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
			?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-exchange"></i></small> <?php echo rex_i18n::msg('d2u_machinery_machine_steel_extension') .' - '. rex_i18n::msg('d2u_machinery_steel_automation_degrees'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($automations) > 0) {
						print '<ul>';
						foreach($automations as $automation) {
							print '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_steel_processing_extension/automation', ['entry_id' => $automation->automation_id, 'func' => 'edit']) .'">'. $automation->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-flask"></i></small> <?php echo rex_i18n::msg('d2u_machinery_machine_steel_extension') .' - '. rex_i18n::msg('d2u_machinery_steel_material_class'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($materials) > 0) {
						print '<ul>';
						foreach($materials as $material) {
							print '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_steel_processing_extension/material', ['entry_id' => $material->material_id, 'func' => 'edit']) .'">'. $material->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-tasks"></i></small> <?php echo rex_i18n::msg('d2u_machinery_machine_steel_extension') .' - '. rex_i18n::msg('d2u_machinery_steel_procedures'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($procedures) > 0) {
						print '<ul>';
						foreach($procedures as $procedure) {
							print '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_steel_processing_extension/procedure', ['entry_id' => $procedure->procedure_id, 'func' => 'edit']) .'">'. $procedure->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-sort-numeric-asc"></i></small> <?php echo rex_i18n::msg('d2u_machinery_machine_steel_extension') .' - '. rex_i18n::msg('d2u_machinery_steel_processes'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($processes) > 0) {
						print '<ul>';
						foreach($processes as $process) {
							print '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_steel_processing_extension/process', ['entry_id' => $process->process_id, 'func' => 'edit']) .'">'. $process->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-i-cursor"></i></small> <?php echo rex_i18n::msg('d2u_machinery_machine_steel_extension') .' - '. rex_i18n::msg('d2u_machinery_steel_profiles'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($profiles) > 0) {
						print '<ul>';
						foreach($profiles as $profile) {
							print '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_steel_processing_extension/profile', ['entry_id' => $profile->profile_id, 'func' => 'edit']) .'">'. $profile->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-stack-overflow"></i></small> <?php echo rex_i18n::msg('d2u_machinery_machine_steel_extension') .' - '. rex_i18n::msg('d2u_machinery_steel_supply'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($supplies) > 0) {
						print '<ul>';
						foreach($supplies as $supply) {
							print '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_steel_processing_extension/supply', ['entry_id' => $supply->supply_id, 'func' => 'edit']) .'">'. $supply->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-magnet"></i></small> <?php echo rex_i18n::msg('d2u_machinery_machine_steel_extension') .' - '. rex_i18n::msg('d2u_machinery_steel_tools'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($tools) > 0) {
						print '<ul>';
						foreach($tools as $tool) {
							print '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_steel_processing_extension/tool', ['entry_id' => $tool->tool_id, 'func' => 'edit']) .'">'. $tool->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-magic"></i></small> <?php echo rex_i18n::msg('d2u_machinery_machine_steel_extension') .' - '. rex_i18n::msg('d2u_machinery_steel_welding'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($weldings) > 0) {
						print '<ul>';
						foreach($weldings as $welding) {
							print '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_steel_processing_extension/welding', ['entry_id' => $welding->welding_id, 'func' => 'edit']) .'">'. $welding->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<?php
				}
				if(rex_plugin::get('d2u_machinery', 'machine_usage_area_extension')->isAvailable()) {
					$usage_areas = UsageArea::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
			?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-codepen"></i></small> <?php echo rex_i18n::msg('d2u_machinery_usage_areas'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($usage_areas) > 0) {
						print '<ul>';
						foreach($usage_areas as $usage_area) {
							print '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_usage_area_extension', ['entry_id' => $usage_area->usage_area_id, 'func' => 'edit']) .'">'. $usage_area->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<?php
				}
				if(rex_plugin::get('d2u_machinery', 'used_machines')->isAvailable()) {
					$used_machines = UsedMachine::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
			?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-truck"></i></small> <?php echo rex_i18n::msg('d2u_machinery_used_machines'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($used_machines) > 0) {
						print '<ul>';
						foreach($used_machines as $used_machine) {
							print '<li><a href="'. rex_url::backendPage('d2u_machinery/used_machines', ['entry_id' => $used_machine->used_machine_id, 'func' => 'edit']) .'">'. $used_machine->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<?php
				}
			?>
		</div>
	</div>
<?php
	}
	
	if(rex_addon::get('d2u_staff')->isAvailable()) {
		$staff_members = Staff::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?php print rex_i18n::msg('d2u_staff'); ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon fa-user-circle"></i></small> <?php echo rex_i18n::msg('d2u_staff_staff'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($staff_members) > 0) {
						print '<ul>';
						foreach($staff_members as $staff_member) {
							print '<li><a href="'. rex_url::backendPage('d2u_staff/staff', ['entry_id' => $staff_member->staff_id, 'func' => 'edit']) .'">'. $staff_member->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
		</div>
	</div>
<?php
	}
	
	if(rex_addon::get('d2u_news')->isAvailable()) {
		$news = News::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?php print rex_i18n::msg('d2u_news'); ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon fa-newspaper-o"></i></small> <?php echo rex_i18n::msg('d2u_news_news_title'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($news) > 0) {
						print '<ul>';
						foreach($news as $current_news) {
							print '<li><a href="'. rex_url::backendPage('d2u_news/news', ['entry_id' => $current_news->news_id, 'func' => 'edit']) .'">'. $current_news->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
		</div>
	</div>
<?php
	}
	
	if(rex_addon::get('d2u_references')->isAvailable()) {
		$references = Reference::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
		$tags = Tag::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?php print rex_i18n::msg('d2u_references'); ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon fa-thumbs-o-up"></i></small> <?php echo rex_i18n::msg('d2u_references_references'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($references) > 0) {
						print '<ul>';
						foreach($references as $reference) {
							print '<li><a href="'. rex_url::backendPage('d2u_references/reference', ['entry_id' => $reference->reference_id, 'func' => 'edit']) .'">'. $reference->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
			<fieldset>
				<legend><small><i class="rex-icon fa-tags"></i></small> <?php echo rex_i18n::msg('d2u_references_tags'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($tags) > 0) {
						print '<ul>';
						foreach($tags as $tag) {
							print '<li><a href="'. rex_url::backendPage('d2u_references/tag', ['entry_id' => $tag->tag_id, 'func' => 'edit']) .'">'. $tag->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
		</div>
	</div>
<?php
	}
	
	if(rex_addon::get('d2u_videos')->isAvailable()) {
		$videos = Video::getTranslationHelperObjects($_SESSION['d2u_helper_translation']['clang_id'], $_SESSION['d2u_helper_translation']['filter']);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?php print rex_i18n::msg('d2u_videos'); ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon fa-video-camera"></i></small> <?php echo rex_i18n::msg('d2u_news_news_title'); ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
					if(count($videos) > 0) {
						print '<ul>';
						foreach($videos as $video) {
							print '<li><a href="'. rex_url::backendPage('d2u_videos/videos', ['entry_id' => $video->video_id, 'func' => 'edit']) .'">'. $video->name .'</a></li>';
						}
						print '</ul>';
					}
					else {
						print $_SESSION['d2u_helper_translation']['filter'] == 'update' ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
					}
				?>
				</div>
			</fieldset>
		</div>
	</div>
<?php
	}
	
print d2u_addon_backend_helper::getCSS();
print d2u_addon_backend_helper::getJS();
print d2u_addon_backend_helper::getJSOpenAll();
}