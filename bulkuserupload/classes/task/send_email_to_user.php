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
 * Adhoc task to send email to users of local_bulkuserupload.
 *
 * @package    local_bulkuserupload
 * @author     Digvijay Singh Bisht (vickybisht524@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_bulkuserupload\task;

use stdClass;

class send_email_to_user extends \core\task\adhoc_task {

    /**
     * Execute the task to send queued email to users.
     */
    public function execute() {
        global $USER, $DB;

        mtrace("Sending queued mail to user of local_bulkuserupload plugin.");

        $data = $this->get_custom_data();

        // From user.
        $fromuser = $USER;

        // To user.
        // (Ugly hack) As email_to_user() need a USER object.
        $touser = clone $USER;
        $touser->email = $data->email;
        $touser->firstname = $data->firstname;
        $touser->lastname = $data->lastname;

        // Subject of the email.
        $subject = get_string('sendrandomemailtousers_subject', 'local_bulkuserupload');

        // Body of the email.
        $body = get_config('local_bulkuserupload', 'sendrandomemailtousers');

        // Send mail.
        $mailsent = email_to_user($touser, $fromuser, $subject, '', $body);

        // If mail is sent update mail sent timings in the bulkuserupload_usersetails table.
        if ($mailsent) {
            $userdetail = new stdClass();
            $userdetail->id = $data->id;
            $userdetail->mailsent = time();
            $DB->update_record('bulkuserupload_userdetails', $userdetail);
        }

    }
}
