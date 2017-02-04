define(['jquery', 'core/ajax', 'core/notification', 'core/event'], function($, ajax, notification, event) {

    var submit_buttons = function () {
        return $('div.que input.submit');
    };

    var outcome_div = function (slot) {
        return $('div#q' + slot + ' div.outcome');
    };

    var question_div = function (slot) {
        return $('div#q' + slot);
    };

    var replace_question_slot_html = function (response) {
        for (var i = 0; i < response.questions.length; i++) {
            var question = response.questions[i];
            if (question_white_listed(question_div(question.slot))) {
                question_div(question.slot).replaceWith(question.html);
                event.notifyFilterContentUpdated($(question_div(question.slot)));

                outcome_div(question.slot).hide().slideDown('slow');
                $("body").css("cursor", "default");
            }
        }
        click_for_whitelisted_qs();
    };

    var replace_navigation_panel_html = function (response) {

        $("#mod_quiz_navblock div.content").html(response.navigationpanelhtml);
    };

    var submit_button_click = function (event) {
        if (event.target.name != '') {
            event.preventDefault();
            var formdata = $(this.form).serializeArray();
            formdata.push({name: event.target.name, value: event.target.value});
            $("body").css("cursor", "progress");
            $(event.target).prop('disabled', 'disabled');
            $(event.target).prop('value', checkingstring);
            var attemptid = null;
            var page = null;
            for (var i = 0; i < formdata.length; i++) {
                if (formdata[i].name == 'thispage') {
                    page = formdata[i].value;
                } else if (formdata[i].name == 'attempt') {
                    attemptid = formdata[i].value;
                }
            }
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
                },
                {
                    methodname: 'quizaccess_ajaxcheck_get_navigation_panel_html',
                    args: {
                        attemptid: attemptid,
                        page: page
                    }
                }
            ]);
            wscalls[0].fail(notification.exception);
            wscalls[1].done(replace_question_slot_html).fail(notification.exception);
            wscalls[2].done(replace_navigation_panel_html).fail(notification.exception);

        }

    };

    var question_white_listed = function (qdiv) {
        //second class name in div.que is qtype
        var qtype = $(qdiv).attr('class').split(' ')[1];
        return ($.inArray(qtype, whitelist) !== -1);
    };

    var click_for_whitelisted_qs = function () {
        submit_buttons().each(function() {
            if (question_white_listed($(this).closest('div.que'))){
                $(this).click(submit_button_click);
            }
        });
    };

    var checkingstring;
    var whitelist;

    var setup = function (checkingstringarg, whitelistarg) {
        checkingstring = checkingstringarg;
        whitelist = whitelistarg;
        click_for_whitelisted_qs();
    };

    return {setup : setup};
});
