/*
 * Function to Handle the Poll section
 * Used for poll options.
 */
(function($) {
    inputCount = 3;
    var MyPoll = function(element, options)
    {
        var elem = $(element);
        var obj = this;
        var settings = $.extend({
            param: 'defaultValue'
        }, options || {});

        this.init = function(newConfig)
        {
            if (typeof newConfig === "object") {
                this.pollFormId = newConfig.pollFormId;
                this.pollHolder = newConfig.pollHolder;
                this.pollOptHolder = newConfig.pollOptHolder;
                this.pollOptClass = newConfig.pollOptClass;
                this.pollOptLimit = newConfig.pollOptLimit;
                this.pollOptLimitCurrent = 3;
            }

            var last_option = this.pollOptHolder + ' ' + this.pollOptClass + ':last input[type="text"]';

            $(document).on("focus", last_option, function() {

                if (inputCount < obj.pollOptLimit)
                {
                    $(Poll.pollOptHolder + ' ' + Poll.pollOptClass).last().clone(true, true).appendTo(Poll.pollOptHolder);
                    $(this).attr('placeholder', 'Option');
                    $(this).attr('name', 'data[Post][poll_options][]');
                    inputCount = inputCount + 1;
                }
                else
                {
                    $(this).attr('placeholder', 'Option');
                }
            });
        };

        this.publicMethod = function()
        {
//            console.log('public method called!');
        };

        // Private method - can only be called from within this object
        var privateMethod = function()
        {
//            console.log('private method called!');
        };
    };

    $.fn.mypoll = function(options)
    {
        return this.each(function()
        {
            var element = $(this);

            // pass options to plugin constructor
            var myplugin = new MyPoll(this, options);

            // Store plugin object in this element's data           
            return myplugin.init(options);
        });
    };
})(jQuery);

var Poll = {
    pollFormId: "#frm_group_poll",
    pollHolder: "#ask_section",
    pollOptHolder: "#option_area",
    pollOptClass: ".poll_inputarea",
    pollOptLimit: 10
}

$(document).on('click', '.vote_for_poll', function() {
    var submitBtn = this;
    var pollId = $(submitBtn).closest('.posting_area').find('#poll_id_hidden').val();
    var optionId = $(submitBtn).closest('.posting_area').find('input[name=optionsRadios]:checked').attr('id');
    var leaveCommunityBtn = $('.btn_leave');
    var loading = Ladda.create(submitBtn);
    loading.start();
    $.ajax({
        type: 'POST',
        url: '/post/api/updatePollVote/poll_id:' + pollId + '/option_id:' + optionId,
        dataType: 'json',
        beforeSend: function() {
            $(submitBtn).attr('disabled', 'disabled');
            leaveCommunityBtn.attr('disabled', 'disabled');
        },
        success: function(result) {
            $(submitBtn).removeAttr('disabled');
            leaveCommunityBtn.removeAttr('disabled');
            loading.stop();
            if (result.success === true) {
                $(submitBtn).attr('disabled', 'disabled');
//                    $(submitBtn).closest('.posting_area').remove();
                $(submitBtn).closest('.posting_area').replaceWith(result.content);
				socket.emit('poll_update', {
					room:$('#PostPostedInRoom').val(),
					attendedUsers: result.attendedUsers,
					content : result.content,
					pollId : pollId
				} );
//                    $('#post_list').prepend(result.content);
                setTimeout(function() {
                    applyTimeAgo();
                }, 60000);
                if ($('#post_container').hasClass('hide')) {
                    $('.alert').hide();
                    $('#post_container').removeClass('hide');
                }
            }
            else if (result.error === true) {
                bootbox.alert(result.message, function() {
                    window.location.reload();
                    window.scrollTo(0, 0);
                });
            }
        }
    });
});
// The post button will be inactive till the user enters text in the posting area
$(document).on('keyup', '#PollForm_title, .poll_input', function() {
    isPollDataPresent = false;
    var isFirstOptionPresent = ($.trim($(".poll_input").first().val()) !== '') ? true : false;
    var isTitlePresent = ($.trim($("#PollForm_title").val()) !== '') ? true : false;
    isPollDataPresent = (isTitlePresent && isFirstOptionPresent) ? true : false;
    changeShareBtnStatus();
});

function resetPollForm()
{
    var totalCount = $('#option_area div.poll_inputarea').length;
    $('#PollForm_title').val("");
    $('.poll_input').val("");
    if (totalCount > 3)
    {
        $('#option_area').find("div").slice(-(totalCount - 1), -2).remove();
    }
    $('#option_area div:last-child').children('input').attr('placeholder', 'add an option');
    inputCount = 3;
    isPollDataPresent = false;
}
function showPollErrorMessage(error) {
    alert(error.error_message);
    $('#PollFormErrorMessage').text(error.error_message).show();
}
function validatePollForm() {
    var options = [];
    var error = false;
    var pos = 0;
    var error_message = " ";
    $('div#posting_errors').html('').hide();
    if ($.trim($("#PollForm_title").val()) != '' && $.trim($("#PollForm_title").val()) != null) {
        $(".poll_input").each(function() {
            if ($.trim($(this).val()) != null && $.trim($(this).val()) != '') {
                pos = $.inArray($(this).val(), options);
                if (pos == -1) {
                    options.push($(this).val());
                } else {
                    error = true;
                    error_message = "Options have duplicate values.";

                }
            }
        });
        if (error !== true && options.length < 2) {
            error = true;
            error_message = "Please enter minimum two options.";
        }
    } else {
        error = true;
        error_message = "Please enter poll title.";
    }
    if (error == true) {
        $('div#posting_errors').html('<span>' + error_message + '</span>').show();
        return false;
    } else {
        return true;
    }
}

$(document).on('click', '.poll_options_radios', function() {
    var radio_selected = this;
    $(radio_selected).closest('.posting_area').find('.vote_for_poll').removeAttr('disabled');
});

$(document).on('click', '.poll_popup_area', function() {
    var self = this;
    var poll_id = $(self).closest('.posting_area').find('#poll_id_hidden').val();
    $('.poll_result_modal').remove();
    $.ajax({
        type: 'POST',
        url: '/post/api/getPollDetails/poll_id:' + poll_id,
        dataType: 'json',
        beforeSend: function() {
            $(self).removeClass('poll_popup_area');
        },
        success: function(result) {
            $(self).addClass('poll_popup_area');
            $('#post_list').prepend(result.content);
//            $('#pollPopupModal #pollPopupModalBody').html(result.content);
            $('#pollPopupModal').modal('show');
        }
    });
});
