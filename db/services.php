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
 * Web service for 'ajaxcheck' quiz access rule plugin external functions and service definitions.
 *
 * @package    quizaccess_ajaxcheck
 * @copyright  2017 Jamie Pratt (me@jamiep.org)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// We defined the web service functions to install.
$functions = array(
        'quizaccess_ajaxcheck_get_navigation_panel_html' => array(
                'classname'   => 'quizaccess_ajaxcheck_external',
                'methodname'  => 'get_navigation_panel_html',
                'description' => 'get html code for navigation panel',
                'type'        => 'read',
                'ajax'        => true,
                'services'    => array('quiz access ajax check ajax ws')
        ),
        'quizaccess_ajaxcheck_process_attempt' => array(
            'classname'     => 'mod_quiz_external',
            'methodname'    => 'process_attempt',
            'description'   => 'Process responses during an attempt at a quiz and also deals with attempts finishing.',
            'type'          => 'write',
            'capabilities'  => 'mod/quiz:attempt',
            'ajax'          => true,
            'services'      => array('quiz access ajax check ajax ws')
        ),
        'quizaccess_ajaxcheck_get_question_html' => array(
            'classname'     => 'quizaccess_ajaxcheck_external',
            'methodname'    => 'get_question_html',
            'description'   => 'Returns html for a given question in a slot for a quiz attempt in progress',
            'type'          => 'read',
            'capabilities'  => 'mod/quiz:attempt',
            'ajax'          => true,
            'services'      => array('quiz access ajax check ajax ws')
        ),

);

