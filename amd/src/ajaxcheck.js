define(['jquery', 'core/ajax'], function($, ajax) {

    var submit_buttons = function () {
        return $('div.que input.submit');
    };

    var outcome_div = function (slot) {
        return $('div#q' + slot + ' div.outcome');
    };

    var question_div = function (slot) {
        return $('div#q' + slot);
    };

    var process_ajax_response = function (response) {
        console.log("Yay. 'qtype_ebox_check_question' web service call suceeded. Response :", response);
        for (var i = 0; i < response.questions.length; i++) {
            var question = response.questions[i];
            question_div(question.slot).replaceWith(question.html);

            outcome_div(question.slot).slideDown('slow');
            $("body").css("cursor", "default");
        }
        submit_buttons().click(submit_button_click);
    };

    var submit_button_click = function (event) {
        if (event.target.name != '') {
            event.preventDefault();
            var formdata = $(this.form).serializeArray();
            formdata.push({name: event.target.name, value: event.target.value});
            $("body").css("cursor", "progress");
            $(event.target).prop('disabled', 'disabled');
            checkstring = $(event.target).prop('value');
            $(event.target).prop('value', checkingstring);
            var attemptid = null;
            var page = null;
            for (var i = 0; i < formdata.length; i++) {
                if (formdata[i].name == 'thispage') {
                    page = formdata[i].value;
                } else if (formdata[i].name == 'attempt') {
                    attemptid = formdata[i].value;
                } else if (/^q[0-9]+:[0-9]+_[-a-z]+$/.test(formdata[i].name)) {
                    var nameparts = /^q[0-9]+:([0-9]+)_([-a-z]+)$/.exec(formdata[i].name);
                    $('div#q' + nameparts[1] + ' div.outcome').hide();
                }
            }
            console.log(attemptid, page, formdata);
            var wscalls = ajax.call([
                {
                    methodname: 'quizaccess_ajaxcheck_process_attempt',
                    args: {
                        attemptid: attemptid,
                        data: formdata
                    }
                },
                {
                    methodname: 'quizaccess_ajaxcheck_get_attempt_data',
                    args: {
                        attemptid: attemptid,
                        page: page
                    }
                }
            ]);
            wscalls[0].fail(
                function (ex) {
                    console.log("Oops. " +
                        "'quizaccess_ajaxcheck_process_attempt' web service call failed. " +
                        "Exception :", ex);
                });
            wscalls[1].done(process_ajax_response)
                .fail(
                function (ex) {
                    console.log("Oops. " +
                        "'quizaccess_ajaxcheck_get_attempt_data' web service call failed. " +
                        "Exception :", ex);
                });

        }

    };

    var checkstring;
    var checkingstring;

    var setup = function (checkingstringarg) {
        checkingstring = checkingstringarg;
        submit_buttons().click(submit_button_click);
    };

    return {setup : setup}
});
