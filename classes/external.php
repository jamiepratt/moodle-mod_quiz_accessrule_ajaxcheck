<?php

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
 * External Web Services
 *
 * @package    quizaccess_ajaxcheck
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");
require_once($CFG->dirroot . '/mod/quiz/locallib.php');


//We're extending mod_quiz_external in order not to have to duplicate the validate_attempt method.

class quizaccess_ajaxcheck_external extends mod_quiz_external {

    /**
     * Describes the parameters for get_navigation_panel_html.
     *
     * @return external_function_parameters
     * @since Moodle 3.1
     */
    public static function get_navigation_panel_html_parameters() {
        return new external_function_parameters (
            array(
                'attemptid' => new external_value(PARAM_INT, 'attempt id'),
                'page' => new external_value(PARAM_INT, 'page number')
            )
        );
    }

    /**
     * Returns information for the given attempt page for a quiz attempt in progress.
     *
     * @param int $attemptid attempt id
     * @param int $page page number
     * @return array with the html for the navigation panel for this page
     * @since Moodle 3.1
     * @throws moodle_quiz_exceptions
     */
    public static function get_navigation_panel_html($attemptid, $page) {
        global $PAGE;
        $params = array(
            'attemptid' => $attemptid,
            'page' => $page
        );
        $params = self::validate_parameters(self::get_navigation_panel_html_parameters(), $params);

        list($attemptobj, $messages) = self::validate_attempt($params);

        $result = array();
        $result['messages'] = $messages;

        $output = $PAGE->get_renderer('mod_quiz');
        $block = $attemptobj->get_navigation_panel($output, 'quiz_attempt_nav_panel', $page);
        $result['navigationpanelhtml'] = $block->content;

        return $result;
    }

    /**
     * Describes the get_navigation_panel_html return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_navigation_panel_html_returns() {
        return new external_single_structure(
            array(
                'messages' => new external_multiple_structure(
                    new external_value(PARAM_TEXT, 'access message'),
                    'access messages, will only be returned for users with mod/quiz:preview capability,
                    for other users this method will throw an exception if there are messages'),
                'navigationpanelhtml' => new external_value(PARAM_RAW, 'the navigation panel rendered')
            )
        );
    }


    /**
     * Describes the parameters for get_question_html.
     *
     * @return external_function_parameters
     * @since Moodle 3.1
     */
    public static function get_question_html_parameters() {
        return new external_function_parameters (
            array(
                'attemptid' => new external_value(PARAM_INT, 'attempt id'),
                'slot' => new external_value(PARAM_INT, 'question slot number'),
            )
        );
    }

    /**
     * Returns information for the given attempt page for a quiz attempt in progress.
     *
     * @param int $attemptid attempt id
     * @param int  $slot  integer slot number
     * @return array of warnings and messages, the question html
     * @throws moodle_quiz_exceptions
     */
    public static function get_question_html($attemptid, $slot) {
        global $PAGE;

        $params = array(
            'attemptid' => $attemptid,
            'slot' => $slot
        );
        $params = self::validate_parameters(self::get_question_html_parameters(), $params);

        list($attemptobj, ) = self::validate_attempt($params);

        $renderer = $PAGE->get_renderer('mod_quiz');

        $question = array(
            'slot' => $slot,
            'html' => $attemptobj->render_question($slot, false, $renderer) . $PAGE->requires->get_end_code()
        );

        return $question;
    }

    /**
     * Describes the get_question_html return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_question_html_returns() {
        return new external_single_structure(
            array(
                'slot' => new external_value(PARAM_INT, 'slot number'),
                'html' => new external_value(PARAM_RAW, 'the question rendered'),
            )
        );
    }

}
