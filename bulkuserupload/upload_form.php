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
 * Form to upload CSV of users.
 *
 * @package    local_bulkuserupload
 * @author     Digvijay Singh Bisht (vickybisht524@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . "/formslib.php");

// Form for uploading CSV of users.
class csv_upload_form extends moodleform {

    // Definition function.
    public function definition() {

        $mform = $this->_form;

        $link = example_csv_link();
        $mform->addElement('static', 'examplecsv', get_string('examplecsv', 'local_bulkuserupload'), $link);
        $mform->addHelpButton('examplecsv', 'examplecsv', 'tool_uploaduser');

        // File picker element to upload CSV file.
        $mform->addElement('filepicker', 'csvofusers', get_string('addcsvfile', 'local_bulkuserupload'),
        null, ['accepted_types' => ['.csv']]);
        $mform->setType('csvofusers', PARAM_FILE);
        $mform->addRule('csvofusers', null, 'required', null, 'client');

        $this->add_action_buttons(false, get_string('submit'));
    }

}

// Form for confirming data of CSV before uploading.
class confirm_csv_form extends moodleform {

    // Definition function.
    public function definition() {

        $mform = $this->_form;

        $data = $this->_customdata['data'];

        // CIR id hidden field to confirm uploading of csv.
        $mform->addElement('hidden', 'cir_id');
        $mform->setType('cir_id', PARAM_INT);

        $mform->addElement('button', 'confirmupload', get_string('confirmupload', 'local_bulkuserupload'));

        $this->set_data($data);
    }

}
