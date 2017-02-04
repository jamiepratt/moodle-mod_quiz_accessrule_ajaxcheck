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
 * Strings for the quizaccess_ajaxcheck plugin.
 *
 * @package    quizaccess_ajaxcheck
 * @copyright 2017 Jamie Pratt (me@jamiep.org)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


$string['ajaxcheck'] = 'Check student response using AJAX';
$string['ajaxcheck_help'] = 'If you enable this option, when students take the quiz and press the Check button '.
    'their answer is checked using AJAX.';
$string['ajaxcheckdefault_help'] = 'When creating a new quiz should the quiz use AJAX by default? Can be overridden on '.
                                    'a per quiz basis in the Appearance section of the Quiz settings.';
$string['pluginname'] = 'AJAX for student response checking';
$string['checking'] = 'Checking.....';
$string['qtypewhitelist'] = 'Use AJAX with these question types';
$string['qtypewhitelist_help'] = 'The JS code in some question types is broken by ajax that changes the html in the '.
                            'page. This setting allows us to enable question types that work with our ajax. The options '.
                            'enabled by default should work with the AJAX.';
