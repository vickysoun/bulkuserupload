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
 * Renderer for local_bulkuserupload.
 *
 * @package    local_bulkuserupload
 * @author     Digvijay Singh Bisht (vickybisht524@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class local_bulkuserupload_renderer extends plugin_renderer_base {

    /**
     * Table to show CSV data before uploading
     * @param array CSV records of users
     * @return string User details table
     */
    public function csv_data_confirm_table($csvdata) {

        $columns = ["S.no.", "Firstname", "Lastname", "E-mail"];

        $table = new html_table();
        $table->head = $columns;
        $table->data = [];

        $i = 1;
        foreach ($csvdata as $record) {
            $row = [
                $i,
                $record['firstname'],
                $record['lastname'],
                $record['email'],
            ];
            $table->data[] = $row;
            $i++;
        }

        $output = html_writer::table($table);

        return $output;
    }

    /**
     * Table to show list of all uploaded users
     * @param object All uploaded users
     * @return string Users details table
     */
    public function all_users_details_table($usersdetails) {

        // If records are available then show table else show no records message.
        if ($usersdetails) {
            $columns = ["S.no.", "Firstname", "Lastname", "E-mail", "E-mail Sent", "E-mail Timings"];

            $table = new html_table();
            $table->head = $columns;
            $table->data = [];

            $i = 1;
            foreach ($usersdetails as $userdetail) {
                $row = [
                    $i,
                    $userdetail->firstname,
                    $userdetail->lastname,
                    $userdetail->email,
                    $userdetail->mailsent == 0 ? "No" : "Yes",
                    $userdetail->mailsent == 0 ? "--" : date('h:i A d M, Y', $userdetail->mailsent),
                ];
                $table->data[] = $row;
                $i++;
            }

            $output = html_writer::table($table);
        } else {
            $output = html_writer::tag('div', get_string('norecordmessage', 'local_bulkuserupload'),
            ['class' => 'alert alert-info text-center mt-5']);
        }

        return $output;
    }

}
