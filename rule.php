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
 * Implementaton of the quizaccess_ajaxcheck plugin.
 *
 * @package   quizaccess
 * @subpackage ajaxcheck
 * @copyright 2017 Jamie Pratt (me@jamiep.org)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/quiz/accessrule/accessrulebase.php');


/**
 * A rule requiring the student to promise not to cheat.
 *
 * @copyright 2017 Jamie Pratt (me@jamiep.org)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_ajaxcheck extends quiz_access_rule_base {

    public static function make(quiz $quizobj, $timenow, $canignoretimelimits) {
        if (empty($quizobj->get_quiz()->ajaxcheck)) {
            return null;
        }

        return new self($quizobj, $timenow);
    }

    public static function add_settings_form_fields(
            mod_quiz_mod_form $quizform, MoodleQuickForm $mform) {
        $mform->addElement($mform->createElement('selectyesno', 'ajaxcheck',
            get_string('ajaxcheck', 'quizaccess_ajaxcheck')));

        $mform->setDefault('ajaxcheck', get_config('quizaccess_ajaxcheck', 'ajaxcheck'));
        $mform->addHelpButton('ajaxcheck',
                'ajaxcheck', 'quizaccess_ajaxcheck');
    }

    public static function save_settings($quiz) {
        global $DB;
        if (empty($quiz->ajaxcheck)) {
            $DB->delete_records('quizaccess_ajaxcheck', array('quizid' => $quiz->id));
        } else {
            if (!$DB->record_exists('quizaccess_ajaxcheck', array('quizid' => $quiz->id))) {
                $record = new stdClass();
                $record->quizid = $quiz->id;
                $record->ajaxcheck = 1;
                $DB->insert_record('quizaccess_ajaxcheck', $record);
            }
        }
    }

    public static function get_settings_sql($quizid) {
        return array(
            'COALESCE(ajaxcheck, 0) AS ajaxcheck',// Using COALESCE to replace NULL with 0.
            'LEFT JOIN {quizaccess_ajaxcheck} qa_gbc ON qa_gbc.quizid = quiz.id',
            array());
    }
}
