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
 * Index file of local_bulkuserupload.
 *
 * @package    local_bulkuserupload
 * @author     Digvijay Singh Bisht (vickybisht524@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");
require_once($CFG->dirroot . "/local/bulkuserupload/lib.php");
require_once($CFG->dirroot . "/local/bulkuserupload/upload_form.php");

// Setting page details.
$PAGE->set_url(new moodle_url("/local/bulkuserupload/index.php"));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string("pluginname", "local_bulkuserupload"));
$PAGE->set_heading(get_string('uploadcsv', 'local_bulkuserupload'));

// Check if user is logged-in and has capability to Upload CSV.
require_login();
if (!has_capability("local/bulkuserupload:uploadcsv", context_system::instance())) {
    redirect(new moodle_url(('/my/')));
}

// Include JS file.
$PAGE->requires->js_call_amd('local_bulkuserupload/bulkuserupload', 'confirmcsv');

// Get renderer of local_bulkuserupload.
$renderer = $PAGE->get_renderer('local_bulkuserupload');

// CSV upload form.
$csvuploadform = new csv_upload_form();

echo $OUTPUT->header();

// Display upload form OR CSV data confirm table if CSV form is submitted.
if ($csvdata = $csvuploadform->get_file_content('csvofusers')) {

    // Process csv data into array.
    $csvirdata = process_csv_data($csvdata);

    $confirmcsvform = new confirm_csv_form(null, ['data' => ['cir_id' => $csvirdata->cir_id]]);

    // Table to show CSV records and button to confirm upload of data.
    echo $renderer->csv_data_confirm_table($csvirdata->userrecords);
    $confirmcsvform->display();
} else {

    // Button to go to list of all users page.
    echo html_writer::link(new moodle_url('/local/bulkuserupload/usersdetails.php'),
    get_string('alluserslist', 'local_bulkuserupload'), ['class' => 'btn btn-info mr-2']);

    // Display CSV upload form.
    $csvuploadform->display();
}

echo $OUTPUT->footer();
