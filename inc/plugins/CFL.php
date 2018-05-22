<?php
/**
 * Plugin Name: Capitalize First Letter In Thread Title
 * Description: This plugin capitalizes the first letter of a thread title.
 * Author: Brian. ( https://community.mybb.com/user-115119.html )
 * Version: 1.1
 * File: CFL.php
**/
 
if(!defined("IN_MYBB"))
{
    	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("datahandler_post_insert_thread", "CFL_newthreads");
$plugins->add_hook("datahandler_post_update_thread", "CFL_editthreads");

function CFL_info()
{
	return array(
		"name"			=> "Capitalize First Letter In Thread Title",
		"description"	=> "This plugin capitalizes the first letter of a thread title.",
		"website"		=> "https://community.mybb.com/user-115119.html",
		"author"		=> "Brian.",
		"authorsite"	=> "https://community.mybb.com/user-115119.html",
		"version"		=> "1.1",
		"compatibility" => "16*,18*"
	);
}

function CFL_activate()
{
	global $db;
	$CFL_settingsgroup = array(
		"gid"    => "NULL",
		"name"  => "CFL_settingsgroup",
		"title"      => "Capitalize First Letter In Thread Title Settings",
		"description"    => "These options allow you to set the plugin to capitalize the first letter of thread titles.",
		"disporder"    => "1",
		"isdefault"  => "no",
	);

	$db->insert_query("settinggroups", $CFL_settingsgroup);
	$gid = $db->insert_id();
	$CFL_capitalthreads = array(
		"sid"            => "NULL",
		"name"        => "CFL_capitalthreads",
		"title"            => "Capitalize first letter in thread title",
		"description"    => "If you would like to capitalize the first letter in a thread\'s title, select yes below.",
		"optionscode"    => "yesno",
		"value"        => "1",
		"disporder"        => "1",
		"gid"            => intval($gid),
	);
	
	$db->insert_query("settings", $CFL_capitalthreads);
  	rebuild_settings();
}

function CFL_newthreads($datahandler)
{
	global $mybb, $db;
		if ($mybb->settings['CFL_capitalthreads'] == 1)
		{
			$datahandler->thread_insert_data['subject'] = ucfirst($datahandler->thread_insert_data['subject']);
		}
}

function CFL_editthreads($datahandler)
{
	global $mybb, $db;
		if ($mybb->settings['CFL_capitalthreads'] == 1 && $datahandler->thread_update_data['subject'])
		{
			$datahandler->thread_update_data['subject'] = ucfirst($datahandler->thread_update_data['subject']);
		}
}

function CFL_deactivate()
{
	global $db;
		$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN('CFL_capitalposts', 'CFL_settingsgroup')");
		$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN('CFL_capitalthreads', 'CFL_settingsgroup')");
		$db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='CFL_settingsgroup'");
		rebuildsettings();
}
?>
