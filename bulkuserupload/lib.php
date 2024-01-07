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
 * Library file for functions of local_bulkuserupload.
 *
 * @package    local_bulkuserupload
 * @author     Digvijay Singh Bisht (vickybisht524@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Generate link to example.csv
 * @return link to example.csv file.
 */
function example_csv_link() {
    $url = new moodle_url("example.csv");
    $link = html_writer::link($url, get_string('examplecsv', 'local_bulkuserupload'));

    return $link;
}

/**
 * Process CSV file data of users.
 * @param string CSV file data.
 * @return object Object of processed data of CSV file and current CIR id.
 */
function process_csv_data($csvdata) {

    global $CFG;
    require_once($CFG->libdir . "/csvlib.class.php");

    // Required columns of CSV.
    $requiredcolumns = ['firstname', 'lastname', 'email'];

    // New CIR instance.
    $id = csv_import_reader::get_new_iid('csvofusers');
    $cir = new csv_import_reader($id, 'csvofusers');

    $cir->load_csv_content($csvdata, 'utf-8', ',');

    // CSV load error handling.
    $csverror = $cir->get_error();
    if (!is_null($csverror)) {
        throw new \moodle_exception('csvloaderror', 'local_bulkuserupload', '', get_string('csvloaderror', 'local_bulkuserupload'));
    }

    // CSV wrong columns error handling.
    if ($cir->get_columns() !== $requiredcolumns) {
        throw new \moodle_exception('csvcolumnerror', 'local_bulkuserupload', '',
        get_string('csvcolumnerror', 'local_bulkuserupload'));
    }

    $cir->init();

    $csvrecords = [];

    while ($cirrecord = $cir->next()) {
        $data = [];
        $data['firstname'] = $cirrecord[0];
        $data['lastname'] = $cirrecord[1];
        $data['email'] = $cirrecord[2];
        $csvrecords[] = $data;
    }

    $output = new stdClass();
    $output->userrecords = $csvrecords;
    $output->cir_id = $id;

    return $output;
}

/**
 * Upload records into bulkuserupload_userdetails table
 * @param int $cirid id of csv import reader
 * @return bool true when records are uploaded
 */
function upload_csv_data ($cirid) {
    global $DB, $CFG;
    require_once($CFG->libdir . "/csvlib.class.php");

    // Using our CIR id again to initialize csv import reader.
    $cir = new csv_import_reader($cirid, 'csvofusers');
    $cir->init();

    // Insert csv records one by one.
    while ($cirrecord = $cir->next()) {
        $data = new stdClass();
        $data->firstname = $cirrecord[0];
        $data->lastname = $cirrecord[1];
        $data->email = $cirrecord[2];
        $data->timecreated = time();

        $data->id = $DB->insert_record('bulkuserupload_userdetails', $data);

        // Queue email for the uploaded user.
        queue_email_adhoc_task($data);
    }

    return true;
}

/**
 * Get all records of bulkuserupload_userdetails table
 * @return object all user records
 */
function get_all_users_details () {
    global $DB;

    $records = $DB->get_records("bulkuserupload_userdetails", null, 'id DESC');

    return $records;
}

/**
 * Queue email for the uploaded user
 * @param object $userdetail User details of the user
 * @return bool true when mail is queued
 */
function queue_email_adhoc_task ($userdetail) {

    $mytask = new local_bulkuserupload\task\send_email_to_user();

    // Set some custom data.
    $mytask->set_custom_data([
        'id' => $userdetail->id,
        'firstname' => $userdetail->firstname,
        'lastname' => $userdetail->lastname,
        'email' => $userdetail->email,
    ]);

    // Queue the task.
    \core\task\manager::queue_adhoc_task($mytask, true);

    return true;
}
