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


$settings->add(new admin_setting_configcheckbox('quizaccess_ajaxcheck/ajaxcheck',
    get_string('ajaxcheck', 'quizaccess_ajaxcheck'),
    get_string('ajaxcheckdefault_help', 'quizaccess_ajaxcheck'),
    0));

$qtypes = core_component::get_plugin_list('qtype');
unset($qtypes['random']);
unset($qtypes['missingtype']);
$qtypechoices = array_combine(array_keys($qtypes), array_keys($qtypes));

$settings->add(new admin_setting_configmulticheckbox('quizaccess_ajaxcheck/whitelist',
    get_string('qtypewhitelist', 'quizaccess_ajaxcheck'),
    get_string('qtypewhitelist_help', 'quizaccess_ajaxcheck'),
    array('calculated' => 1, 'calculatedmulti' => 1, 'calculatedsimple' => 1, 'ddmarker'=> 1,
        'gapselect'=> 1,  'match'=> 1, 'multianswer'=> 1,
        'multichoice'=> 1,  'numerical'=> 1,  'randomsamatch'=> 1,  'shortanswer'=> 1,  'truefalse'=> 1),
    $qtypechoices));
