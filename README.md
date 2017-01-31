A plug in to add a setting to quizzes to use ajax to check responses.
=====================================================================

Operation
---------

This plug in will introduce :

### 1. a new quiz setting 'Check student response using AJAX'

The new setting is at the bottom of the 'Appearance' section of the quiz settings form that you see when you edit a
 quiz or set up a new quiz.

#### 1.1 You can set the default for this setting for new quizzes

See the 'AJAX for student response checking' in the site admin menu under "Plugins / Activity Modules / Quiz / Ajax
for student response checking"

If you have just installed this plug in, then all existing quizzes will default to this setting being off.

### 2. When a quiz is attempted, or previewed 

When using a student presses the 'Check' button, instead of the page reloading the student response is sent up to the 
server using ajax and the appropriate parts of the page are then updated without a page reload.

This includes updating : 

* anywhere in the question formulation and response that normally changes eg. highlighting parts
of the question or feedback that occurs within the question formulation itself.
* any feedback below the question is immediately displayed and brought to the user's attention by a slide down animation.
* the quiz navigation block is updated with the appropriate colours indicating the state of each question.


Compatability
-------------

It can be used with version 3.2+ of Moodle, or later.

Installation
------------

To install using git, type (or copy and paste) these commands in the root of your Moodle install :

    git clone git://github.com/jamiepratt/moodle-mod_quiz_accessrule_ajaxcheck.git mod/quiz/accessrule/ajaxcheck
    echo '/mod/quiz/accessrule/ajaxcheck/' >> .git/info/exclude

Alternatively, download the zip from [this url](https://github.com/jamiepratt/moodle-mod_quiz_accessrule_ajaxcheck/archive/master.zip)
unzip it into the mod/quiz/accessrule folder, and then rename the new
folder to ajaxcheck.

### Install new db records

Once the code is installed you need to go to the Site administration -> Notifications page
to let the plugin install the db table it uses.
