define(['jquery', 'core/ajax', 'core/notification', 'core/event', 'core/yui'],
    function($, ajax, notification, event, Y) {

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
        question_div(response.slot).replaceWith(response.html);
        event.notifyFilterContentUpdated($(question_div(response.slot)));
        outcome_div(response.slot).hide().slideDown('slow');
        $("body").css("cursor", "default");
        click_for_whitelisted_qs();
        for (var i = 0; i < response.sequencechecks.length; i++) {
            var sequencecheck = response.sequencechecks[i].sequencecheck;
            var fieldprefix = response.sequencechecks[i].fieldprefix;
            var sequencecheckinput = $('input[name="' + fieldprefix + ':sequencecheck"]');
            sequencecheckinput.addClass('ignoredirty');
            sequencecheckinput.val(sequencecheck);

        }
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
            var submitbuttonslot = Number($(event.target).closest('div.que').attr('id').substring(1));
            var wscalls = ajax.call([
                {
                    methodname: 'quizaccess_ajaxcheck_process_attempt',
                    args: {
                        attemptid: attemptid,
                        data: formdata
                    }
                },
                {
                    methodname: 'quizaccess_ajaxcheck_get_question_html',
                    args: {
                        attemptid: attemptid,
                        page: page,
                        submitbuttonslot: submitbuttonslot
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

            Y.use('moodle-core-formchangechecker', function() {
                M.core_formchangechecker.reset_form_dirty_state();
            });

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
                //tell formchangechecker to ignore changes made to label of submit button.
                $(this).addClass('ignoredirty');
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
