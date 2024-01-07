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
 * Settings file for local_bulkuserupload.
 *
 * @package    local_bulkuserupload
 * @author     Digvijay Singh Bisht (vickybisht524@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // Link to our plugin local_bulkuserupload.
    $linktoplugin = new admin_externalpage('bulkuserupload_plugin', get_string('pluginname',
    'local_bulkuserupload'), $CFG->wwwroot."/local/bulkuserupload");

    $ADMIN->add('users', $linktoplugin);

    // Setting page to set random email.
    $settings = new admin_settingpage('bulkuserupload', get_string('sendrandomemailtousers', 'local_bulkuserupload'));

    $settings->add(new admin_setting_configtextarea('local_bulkuserupload/sendrandomemailtousers',
        new lang_string('sendrandomemailtousers', 'local_bulkuserupload'),
        new lang_string('sendrandomemailtousers_desc', 'local_bulkuserupload'),
        new lang_string('sendrandomemailtousers_mail', 'local_bulkuserupload'), PARAM_RAW)
    );

    $ADMIN->add('users', $settings);
}
