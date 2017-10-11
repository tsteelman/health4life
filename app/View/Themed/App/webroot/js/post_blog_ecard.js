/*
 * Declare global variables
 */
var POST_TYPE_ECARD = 'ecard';
var POST_TYPE_BLOG = 'blog';

var isEcard = false;
var isBlog = false;

/*
 * Show the blog/ecard posting option
 */
$(document).on('click', '.start_discussion', function() {
	$(".discussion_matter").show();
	$(".start_discussion").hide();

	// show the default form
	$('#posting_blog_form').show();
	$('#PostTitle').focus();
        
	//$('#share_post_btn').attr('disabled', 'disabled');
});

/*
 * Attaching Cancel button action
 */
$(document).on('click', '#cancel_post_btn', function() {
	cancelPosting();
});

function cancelPosting()
{
    $(".discussion_matter").hide();
    $(".start_discussion").show();
    resetForm();
}



/*
 * Function to show or hide the posting options
 */
$(document).on('click', '.posting_items ul.post_options li', function() {
	$(".posting_options_form").hide();
	$(this).parent().find("li").removeClass("active");
	$(this).addClass("active")
	$("#" + $(this).data("elem") + "_form").show();
	var postType = $(this).data("posttype");
	$("#PostPostType").val(postType);
	$("#posting_more_action").show();


	// hide the validation error messages
	$('div#posting_errors').html('').hide();

	// change the share button disabled status
	changeShareBtnStatus();
});

function changeShareBtnStatus() {
	var postType = $('#PostPostType').val();
	var allowPosting = false;
	switch (postType) {
            case POST_TYPE_BLOG:
                    allowPosting = isBlog;
                    break;
            case POST_TYPE_ECARD:
                    allowPosting = isEcard;
                    break;                        
	}
	if (allowPosting === true) {
		//$('#share_post_btn').removeAttr('disabled');
	}
	else {
		//$('#share_post_btn').attr('disabled', 'disabled');
	}
}


/**
 * Function to create post
 */
$(document).on('click', '#share_post_btn', function() {
    var submitBtn = this;
    if (isPostFormValid()) {
		if( $('#PostPostType').val() == POST_TYPE_BLOG) {
             var blogTitle = $("#PostTitle").val();
        }
        var loading = Ladda.create(submitBtn);
        loading.start();
        $.ajax({
            type: 'POST',
            url: '/post/api/createPost',
            data: $(form).serialize(),
            dataType: 'json',
            beforeSend: function() {
                $(submitBtn).attr('disabled', 'disabled');
            },
            success: function(result) {
                //cancelPosting();
                $(submitBtn).removeAttr('disabled');
                loading.stop();
                if (result.success === true) {
                    cancelPosting();
                    if ($('#post_container').hasClass('hide')) {
                        $('#no_posts_msg').addClass('hide');
                        $('#post_container').removeClass('hide');
                    }
                    if( $('#PostPostType').val() == POST_TYPE_BLOG) {
                        $('.latest_blog_title').text(blogTitle);
						$('.comment_time').text("Just now");
                    }
                    bootbox.alert(result.message, function() {
                        if (result.errorType && (result.errorType === 'fatal')) {
                                window.location.reload();
                                window.scrollTo(0, 0);
                        }
                    });
                }
                else if (result.error === true) {
                    bootbox.alert(result.message, function() {
                        if (result.errorType && (result.errorType === 'fatal')) {
                            window.location.reload();
                            window.scrollTo(0, 0);
                        }
                    });
					//}).find('.bootbox-body').addClass("alert-warning");
                } else {
                    bootbox.alert(result);
                }               
            }
        });
    }
});


function isPostFormValid() {
    return true;
}

/**
 * Function to reset post form
 */
function resetForm() {
    $(form)[0].reset();
    $('.posting_options_form').hide();
    $('ul.post_options').find('li').removeClass('active');
    $('ul.post_options').find('li.posting_blog').addClass('active');

    //Reset blog form
    $('#posting_errors').html('').hide();
    $('#PostDescription').code('');
    
    //Reset ecard form
    $('#posting_ecard_form .ecard_link').removeClass("active");
    $('#PostEcardTitle').val('');
    $('#EcardToUserId').val('');       
    $("#posting_ecard_form .token").remove();
    $("#PostEcardSelected").val('');
        
}


$(document).ready(function() {
    // initialize the functionality for sending Ecard
    initEcardSender();
    
    // initialize the functionality for sending Ecard
    initBlogPost();    
	
//	$(window).load(function() {
//		var container = document.querySelector('#blog_container'); 
//		var twoColumn = new Masonry( container, {
//			columnWidth: 10
//		});
//		twoColumn.layout();
//	});
 });

function initEcardSender() {
     
    /*
     * Make the To field autocomplete
     */   
    $('#PostToUser').facelist('/FrontApp/searchUsername', properties = {
        matchContains: true,
        minChars: 2,
        selectFirst: false,
        intro_text: 'Type Userame',
        no_result: 'No users',
        multiple: false,
        result_field: 'EcardToUserId',
        result_list_field: 'ecard_user_list',
        enableImage:true
                
    });    

    /*
     * Implement the click & selection of the ecards
     */
    $(document).on('click', '#posting_ecard_form .ecard_link', function() {    
        if($(this).hasClass("active")) {
            $(this).removeClass("active");
        } else {
            $(this).addClass("active");
        }
        
        if($('#posting_ecard_form .ecard_link.active').length <= 0) {
            $('#share_post_btn').attr('disabled', 'disabled');
        } else {
            var selectedEcard = "";
            $('#share_post_btn').removeAttr('disabled');
            $('#posting_ecard_form .ecard_link.active').each(function( index, value ) {
              selectedEcard += $( this ).data('ecard_id')+",";
            });            
            $("#PostEcardSelected").val(selectedEcard);
        }
    });
    
    /*
     * Make the message adding part as an editor
     */
//    $("#PostEcardTitle").summernote({
//        height: 50,
//        minHeight: 20,
//        maxHeight: 100,        
//        airMode: true,
//        airPopover: [
//           ['color', ['color']],
//           ['font', ['bold', 'underline', 'clear']],
//           ['para', ['ul', 'paragraph']],
//           ['insert', ['link']]
//         ]        
//    });
    
}


/**
 * Function for posting the blog
 */
function initBlogPost() {
    
    /*
     * Initiaize the Editor for the Blog
     */
    $('#PostDescription').summernote({
        height: 200,
        minHeight: 100,
        maxHeight: 800,
        toolbar: [  
          ['style', ['bold', 'italic', 'underline', 'clear']],
          ['font', ['strikethrough']],
          ['fontsize', ['fontsize']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['link', 'video', 'picture']],
          ['misc', ['fullscreen', 'undo', 'redo', 'help']]
        ],
//        onImageUpload: function(files, editor, welEditable) {
//            uploadEditorImage(files[0], editor, welEditable);
//        },   
        
        onpaste: function(e) {
            bootbox.alert("Sorry, pasting is not allowed due to security reasons!");
            e.preventDefault();
            e.stopPropagation();
        },
        onblur: function(e) {
            $('#PostDescription').html($('#PostDescription').code());
        },       
        onkeydown:function(e){
            /*
             * Code to limit the characters
             */
            /*
            var num = $('#PostDescription').code().replace(/(<([^>]+)>)/ig,"").length;
            var key = e.keyCode;
            allowed_keys = [8, 37, 38, 39, 40, 46]
            if($.inArray(key, allowed_keys) != -1)
                return true
            else if(num > 50){
                e.preventDefault();
                e.stopPropagation()
            }
            */
        }        
    });  
    
    function uploadEditorImage(file, editor, welEditable) {
        data = new FormData();
        data.append("qqfile", file);
        $.ajax({
            data: data,
            type: "POST",
            url: '/post/api/previewPhoto',
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',            
            success: function(responseJSON) {               
                if (responseJSON.success) {
                    editor.insertImage(welEditable, responseJSON.orig_fileurl);
					$('#PostPhoto').val(responseJSON.fileName);
                    
                } else {
                    bootbox.alert(responseJSON.error);
                }                
                
            }
        });
    }
    
}

/**
 * Read more click in blog detail page.
 */
$(document).on('click', '.read_more_text', function() {
	var truncatedText = $(this).parent('.truncated_text');
	truncatedText.addClass('hide');
	truncatedText.next('.full_text').removeClass('hide').children('.read_less_text').removeClass('hide');
	$(this).addClass('hide');
	
	var container = document.querySelector('#blog_container'); 
	var twoColumn = new Masonry( container, {
		columnWidth: 10
	});
	twoColumn.layout();
});

/**
 * Less read click in blog detail page.
 */
$(document).on('click', '.read_less_text', function() {
	var fullText = $(this).parent('.full_text');
	fullText.addClass('hide');
	fullText.prev('.truncated_text').removeClass('hide').children('.read_more_text').removeClass('hide');
	$(this).addClass('hide');
	
	var container = document.querySelector('#blog_container'); 
	var twoColumn = new Masonry( container, {
		columnWidth: 10
	});
	twoColumn.layout();
});

