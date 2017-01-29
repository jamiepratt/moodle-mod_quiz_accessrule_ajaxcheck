define(['jquery', 'core/ajax'], function($, ajax) {

    var submit_buttons = function () {
        return $('div.que input.submit');
    };

    var outcome_div = function (slot) {
        return $('div#q' + slot + ' div.outcome');
    };

    var process_ajax_response = function (response) {
        console.log("Yay. 'qtype_ebox_check_question' web service call suceeded. Response :", response);
        for (var i = 0; i < response.outcomes.length; i++) {
            var outcome = response.outcomes[i];
            var outcomediv = outcome_div(outcome.slot);
            if (outcomediv.length) {
                outcomediv.html(outcome.outcome);
            } else {
                $('div#q' + outcome.slot + ' div.content').append(
                    '<div class="outcome clearfix" style="display: none;">' + outcome.outcome + '</div>'
                );
            }
            outcome_div(outcome.slot).slideDown('slow');
            $('div#q' + outcome.slot + ' input.submit').removeProp('disabled');
            $('div#q' + outcome.slot + ' input.submit').prop('value', checkstring);
            $("body").css("cursor", "default");
            $('input[name="' + outcome.fieldprefix + ':sequencecheck"]').val(outcome.sequencecheck);

        }
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
            var slots = null;
            for (var i = 0; i < formdata.length; i++) {
                if (formdata[i].name == 'slots') {
                    slots = formdata[i].value;
                } else if (formdata[i].name == 'attempt') {
                    attemptid = formdata[i].value;
                } else if (/^q[0-9]+:[0-9]+_[-a-z]+$/.test(formdata[i].name)) {
                    var nameparts = /^q[0-9]+:([0-9]+)_([-a-z]+)$/.exec(formdata[i].name);
                    $('div#q' + nameparts[1] + ' div.outcome').hide();
                }
            }
            console.log(attemptid, slots, formdata);
            var wscalls = ajax.call([
                {
                    methodname: 'quizaccess_ajaxcheck_check_question',
                    args: {
                        attemptid: attemptid,
                        slots: slots,
                        formdata: formdata
                    }
                }
            ]);
            wscalls[0].done(process_ajax_response)
                .fail(function (ex) {
                    console.log("Oops. " +
                        "'qtype_ebox_check_question' web service call failed. " +
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
