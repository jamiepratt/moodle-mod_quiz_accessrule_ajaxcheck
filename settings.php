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
 * Administration settings definitions for the quizaccess_ajaxcheck quiz module sub plugin.
 *
 * @package   quizaccess
 * @subpackage ajaxcheck
 * @copyright 2017 Jamie Pratt (me@jamiep.org)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


$quizsettings->add(new admin_setting_configcheckbox('quizaccess_ajaxcheck/ajaxcheck',
    get_string('ajaxcheck', 'quizaccess_ajaxcheck'),
    get_string('ajaxcheck_help', 'quizaccess_ajaxcheck'),
    0));
