<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Defines the form for editing upload video to Vimeo block instances.
 *
 * @package    block_suporte
 * @copyright  2020 CCEAD PUC-Rio
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    
    // Access.
    $settings->add(new admin_setting_heading('block_suporte/config_headingaccess', new lang_string('config_headingaccess', 'block_suporte'), ''));
    
    $name = 'block_suporte/atendimentourl';
    $visiblename = new lang_string('atendimentourl', 'block_suporte');
    $description = new lang_string('atendimentourl_desc', 'block_suporte');
    $defaultsetting = 'https://atendimento.ccead.puc-rio.br';
    $setting = new admin_setting_configtext($name, $visiblename, $description, $defaultsetting);
    $settings->add($setting);
    
    $name = 'block_suporte/dbhost';
    $visiblename = new lang_string('dbhost', 'block_suporte');
    $description = new lang_string('dbhost_desc', 'block_suporte');
    $defaultsetting = 'localhost';
    $setting = new admin_setting_configtext($name, $visiblename, $description, $defaultsetting, PARAM_URL, 50);
    $settings->add($setting);
    
    $name = 'block_suporte/dbname';
    $visiblename = new lang_string('dbname', 'block_suporte');
    $description = new lang_string('dbname_desc', 'block_suporte');
    $defaultsetting = 'hesk255';
    $setting = new admin_setting_configtext($name, $visiblename, $description, $defaultsetting, PARAM_RAW, 30);
    $settings->add($setting);
    
    $name = 'block_suporte/dbuser';
    $visiblename = new lang_string('dbuser', 'block_suporte');
    $description = new lang_string('dbuser_desc', 'block_suporte');
    $defaultsetting = 'root';
    $setting = new admin_setting_configpasswordunmask($name, $visiblename, $description, $defaultsetting, PARAM_RAW, 30);
    $settings->add($setting);
    
    $name = 'block_suporte/dbpass';
    $visiblename = new lang_string('dbpass', 'block_suporte');
    $description = new lang_string('dbpass_desc', 'block_suporte');
    $defaultsetting = '';
    $setting = new admin_setting_configpasswordunmask($name, $visiblename, $description, $defaultsetting, PARAM_RAW, 30);
    $settings->add($setting);

}
