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


class quizaccess_ajaxcheck_external extends external_api {

    public static function check_question_parameters() {
        return new external_function_parameters(
            array('attemptid' => new external_value(PARAM_INT, 'Attmept id'),
                'slots' => new external_value(PARAM_SEQUENCE, 'slots'),
                'formdata' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW),
                            'value' => new external_value(PARAM_RAW)
                        )
                    )
                )
            )
        );
    }


    public static function check_question($attemptid, $slots, $formdata) {
        global $DB, $USER, $PAGE, $_POST;

        $params = self::validate_parameters(self::check_question_parameters(),
            array('attemptid' => $attemptid,
                'slots' => $slots,
                'formdata' => $formdata));

        // Remember the current time as the time any responses were submitted
// (so as to make sure students don't get penalized for slow processing on this page).
        $timenow = time();


        $transaction = $DB->start_delegated_transaction();
        $attemptobj = quiz_attempt::create($attemptid);

// Check login.
        require_login($attemptobj->get_course(), false, $attemptobj->get_cm());
        require_sesskey();

// Check that this attempt belongs to this user.
        if ($attemptobj->get_userid() != $USER->id) {
            throw new moodle_quiz_exception($attemptobj->get_quizobj(), 'notyourattempt');
        }

// Check capabilities.
        if (!$attemptobj->is_preview_user()) {
            $attemptobj->require_capability('mod/quiz:attempt');
        }

// If the attempt is already closed, send them to the review page.
        if ($attemptobj->is_finished()) {
            throw new moodle_quiz_exception($attemptobj->get_quizobj(),
                'attemptalreadyclosed', null, $attemptobj->review_url());
        }

        foreach ($formdata as $f) {
            $_POST[$f['name']] = $f['value'];
        }

        $attemptobj->process_submitted_actions($timenow);

        $transaction->allow_commit();

        // reload attempt data.
        $attemptobj = quiz_attempt::create($attemptid);


        $slots = explode(',', $slots);
        $outcomes = array();
        foreach ($slots as $slot) {
            $qa = $attemptobj->get_question_attempt($slot);
            $qb = $qa->get_behaviour();
            $qbr = $qb->get_renderer($PAGE);
            $qtoutput = $qa->get_question()->get_renderer($PAGE);
            $options = $attemptobj->get_display_options(false);
            $qb->adjust_display_options($options);
            $outcome = new stdClass();
            $outcome->outcome = self::outcome($qa, $qbr, $qtoutput, $options);
            $outcome->sequencecheck = $qa->get_sequence_check_count();
            $outcome->fieldprefix = $qa->get_field_prefix();
            $outcome->slot = $slot;
            $outcomes[] = $outcome;
        }

        $toreturn = new stdClass();
        $toreturn->status = 'done';
        $toreturn->outcomes = $outcomes;
        return $toreturn;
    }

    public static function check_question_returns() {
        return new external_single_structure(
            array (
                'status' => new external_value(PARAM_RAW),
                'outcomes' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'slot' => new external_value(PARAM_INT, 'slot'),
                            'outcome' => new external_value(PARAM_RAW, 'outcome'),
                            'sequencecheck' => new external_value(PARAM_RAW, 'sequencecheck'),
                            'fieldprefix' => new external_value(PARAM_RAW, 'fieldprefix'),
                        )
                    )
                )
            )
        );
    }

    protected static function outcome(question_attempt $qa, qbehaviour_renderer $behaviouroutput,
                               qtype_renderer $qtoutput, question_display_options $options) {
        $output = '';
        $output .= html_writer::tag('h4', get_string('feedback', 'question'), array('class' => 'accesshide'));
        $output .= html_writer::nonempty_tag('div',
            $qtoutput->feedback($qa, $options), array('class' => 'feedback'));
        $output .= html_writer::nonempty_tag('div',
            $behaviouroutput->feedback($qa, $options), array('class' => 'im-feedback'));
        $output .= html_writer::nonempty_tag('div',
            $options->extrainfocontent, array('class' => 'extra-feedback'));

        return $output;
    }

}
