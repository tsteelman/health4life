var ROLE_PATIENT=1,ROLE_FAMILY=2,ROLE_CAREGIVER=3,ROLE_OTHER=4;function removeDobValidations(){$(".dobrow select").each(function(){$(this).rules("remove","groupRequired");$(this).rules("remove","validDOB");$(this).parents(".form-group-container").removeClass("error");$(this).valid()})}
function addDobValidations(){$(".dobrow select").each(function(){var a=parseInt($("#UserType").val())===ROLE_PATIENT?!0:!1;$(this).rules("add",{groupRequired:a,validDOB:!0,messages:{groupRequired:"Enter your date of birth",validDOB:"Minimum age limit is 13 years"}})})}$(document).on("change",".dobrow select",function(){removeDobValidations()});$(document).on("click",'#userEditForm button[type="submit"]',function(){handleCountryZipValidation($("#UserCountry"));addDobValidations();handleGenderValidation()});
function handleGenderValidation(){parseInt($("#UserType").val())===ROLE_PATIENT?$("#UserGender").rules("add",{required:!0,messages:{required:"Please select a Gender"}}):$("#UserGender").rules("remove","required")}function addDiagnosisDateValidationRule(){$.validator.addMethod("isValidDiagonisedDate",function(a,b){var d=$("#UserDob-year").val(),c=$(b).val();return 0<d&&0<c?d<=c:!0})}
function initFaceList(a){var b=$(a).next(".treatment_id_hidden").attr("id");$(a).facelist("/api/searchTreatments",properties={matchContains:!0,minChars:2,selectFirst:!1,intro_text:"Type Name",no_result:"No Names",result_field:b})}$(document).on("focus",".disease_search",function(){initDiseaseAutoComplete(this,1)});
function generate_day_select_box(a){var b=$(a).attr("data-rel"),b=(new String(b)).split("#"),d=$(a).closest(".form-group-container"),c=$(d).find("."+b[2]),e=$(d).find("."+b[1]),d=$(d).find("."+b[0]);$(a).attr("id")==d.attr("id")&&(e.prop("selectedIndex",0),c.prop("selectedIndex",0));$(a).attr("id")==e.attr("id")&&c.prop("selectedIndex",0);var a=c.val(),b=e.val(),f=d.val(),g=(new Date(f,b,0)).getDate(),d=12,h="January February March April May June July August September October November December".split(" "),
i=new Date,j=i.getMonth()+1,k=i.getDate(),i=i.getFullYear();f>=i&&(d=j);f>=i&&b>=j&&(g=k-1);if(""!=f&&""!=b){c.empty();c.append($("<option></option>").val("").html("Day"));for(f=1;f<=g;f++)c.append($("<option>Day</option>").val(f).html(f))}e.empty();e.append($("<option></option>").val("").html("Month"));for(g=1;g<=d;g++)f=g-1,e.append($("<option>Month</option>").val(g).html(h[f]));c.val(a);e.val(b)}
function createUploader(){$upload_btn=$("#bootstrapped-fine-uploader");$messages=$("#uploadmessages");$preview_div=$("#uploadPreview");$preview_img=$("#uploadPreview img");var a=new qq.FineUploaderBasic({button:$upload_btn[0],debug:!1,multiple:!1,request:{endpoint:"/user/register/photo"},validation:{acceptFiles:"image/*",allowedExtensions:["jpeg","jpg","gif","png"],minSizeLimit:"1024",sizeLimit:"5242880"},callbacks:{onError:function(a,d,c){$messages.html('<div class="alert alert-error">'+c+"</div>");
$messages.show();$upload_btn.show();$(".btn-prev").removeAttr("disabled");$(".btn-next").removeAttr("disabled");isUploading=!1},onSubmit:function(){$upload_btn.hide();$messages.show();$messages.html('<div class="alert alert-info">onSubmit</div>')},onUpload:function(a,d){$messages.html('<div class="alert alert-info">'+('<img src="'+app.site_url+'/img/loading.gif" alt="Initializing. Please hold."> Initializing "'+d+'"')+"</div>");$(".btn-prev").attr("disabled","disabled");$(".btn-next").attr("disabled",
"disabled");isUploading=!0},onProgress:function(a,d,c,e){c<e?(progress=Math.round(100*(c/e))+"% of "+Math.round(e/1024)+" kB",$messages.html('<div class="alert alert-info"><img src="'+app.site_url+'/img/loading.gif" alt="In progress. Please hold."> Uploading "'+d+'" '+progress+"</div>")):$messages.show()},onComplete:function(b,d,c){isUploading=!1;$upload_btn.show();if(c.success){$messages.hide();var e=bootbox.dialog({closeButton:!1,message:"test",title:"Profile Photo",animate:!1,buttons:{success:{label:"Accept",
className:"accept btn-success",callback:function(){$.ajax({dataType:"json",type:"POST",url:"/user/register/photo",data:{crop_image:!0,x1:$("#x1").val(),y1:$("#y1").val(),w:$("#w").val(),h:$("#h").val(),cropfileName:c.fileName},beforeSend:function(){$preview_img.attr("src",app.site_url+"theme/App/img/loading.gif")},success:function(a){$("#cropfileName").val(a.fileName);$preview_img.attr("src",a.fileUrl)}})}},cancel:{label:"Cancel",className:"btn-default",callback:function(){a.cancelAll()}}}});e.find(".modal-footer .btn-success").attr("disabled",
"disabled");b='<div class="upload_area"><img src="'+c.fileurl+'" alt="Uploaded photo" /></div>';e.find(".bootbox-body").html(b+'<div>To make adjustments, please drag around the white rectangle below. When you are happy with the photos, click "Accept" button. Your beautiful, clean, non-pixelated image should be at minimum 200x200 pixels.</div>');setTimeout(function(){var a=200;c.imageWidth<a?a=c.imageWidth-2:c.imageHeight<a&&(a=c.imageHeight-2);e.find(".bootbox-body img").imgAreaSelect({parent:".bootbox",
autoHide:!1,mustMatch:!0,handles:!0,instance:!0,aspectRatio:"1:1",x1:0,y1:0,x2:a,y2:a,minHeight:a,minWidth:a,onInit:function(){e.find(".modal-footer .btn-success").removeAttr("disabled")},onSelectStart:function(){e.find(".modal-footer .btn-success").removeAttr("disabled")},onCancelSelection:function(){e.find(".modal-footer .btn-success").attr("disabled","disabled")},onSelectEnd:function(a,b){$("#x1").val(b.x1);$("#y1").val(b.y1);$("#w").val(b.width);$("#h").val(b.height)}});$(".btn-prev").removeAttr("disabled");
$(".btn-next").removeAttr("disabled")},500)}else $messages.show(),$("#uploadmessages .alert").removeClass("alert-info").addClass("alert-error").html('Error with "'+d+'": '+c.error)}}})}
function initRegistration(){detectTimeZone();applyChosen();$(".chosen-select").chosen().change(function(){$(this).valid()});setHideShowPlugin("#UserPassword");setHideShowPlugin("#UserConfirm-password");$("#UserPassword").pwstrength();createUploader();addDiagnosisDateValidationRule();initFaceList("#PatientDisease0UserTreatments")}
function detectTimeZone(){$.ajax({url:"/api/detected_timezone_id_JSON",data:getTimeZoneData(),method:"POST",dataType:"JSON"}).done(function(a){$("#UserTimezone").val(a)})}function removeDiseaseNamesValidation(){$('[name*="[disease_name]"]').each(function(){$(this).rules("remove","required");$(this).valid()})}
$(document).on("click",".roles",function(){$(".role_container .roles").removeClass("active");$(this).addClass("active");var a=parseInt($(this).data("user_type"));$("#UserType").val(a);var b=$(this).find("h3").text()+" SignUp";$("#form_heading").text(b);var b="/theme/App/img/user_default_"+a+"_medium.png",d,c=$(".condition_row .red_star_span"),e=$(".dobrow .red_star_span"),f=$(".gender_row .red_star_span"),g=$(".diagnosis_date_row, .medication_row");if(a===ROLE_PATIENT)g.show(),c.show(),e.show(),f.show(),
a="Condition Details",d="border_patient";else{g.hide();g.each(function(){$(this).find("select, input").val("")});$(".medication_row .facelist li.token").remove();removeDiseaseNamesValidation();c.hide();e.hide();f.hide();var h;switch(a){case ROLE_OTHER:h="friend";d="border_other";break;case ROLE_CAREGIVER:h="patient";d="border_caregiver";break;case ROLE_FAMILY:h="family member",d="border_family"}a="What condition does your "+h+" have?"}$(".condition_heading").text(a);a=$("#uploadPreview img");""===
$("#cropfileName").val()&&a.attr("src",b);a.attr("class","img-circle profile_brdr_5 "+d);removeValidationErrors()});function removeValidationErrors(){removeDobValidations();$("#User").validate().resetForm();$(".form-group").removeClass("error")}$(document).on("blur",".disease_search",function(){""===$(this).next(".disease_id_hidden").val().trim()&&$(this).val("")});
$(document).on("click","#add_condition_btn",function(){var a,b;a=$("#last_condition_index").val();b=parseInt(a)+1;a=$("#sample_condition_row").clone();a.removeClass("hide").removeAttr("id");a.find("input").each(function(){var a=$(this).attr("name"),c=$(this).attr("id"),a=a.replace("index",b),c=c.replace("Index",b);$(this).attr("name",a);$(this).attr("id",c)});$("#conditions_container").append(a);$("#last_condition_index").val(b);initFaceList("#PatientDisease"+b+"UserTreatments")});
$(document).on("click",".condition_row .close",function(){$(this).closest(".condition_row").remove()});$(document).on("change",'[name*="[diagnosis_date_year]"], [name*="[treatment_id]"]',function(){if(parseInt($("#UserType").val())!==ROLE_PATIENT){var a=$(this).closest(".condition_row"),b=a.find('[name*="[disease_name]"]'),d=a.find('[name*="[diagnosis_date_year]"]'),a=a.find('[name*="[treatment_id]"]');""===d.val()&&""===a.val()&&(b.rules("remove","required"),b.valid())}});
function addDiagnosisValidations(){$(".condition_row").each(function(){var a=$(this).find('[name*="[disease_name]"]'),b=$(this).find('[name*="[diagnosis_date_year]"]');if(parseInt($("#UserType").val())===ROLE_PATIENT)a.rules("add",{required:!0,messages:{required:"Please enter a valid diagnosis"}});else{var d=$(this).find('[name*="[treatment_id]"]');""===b.val()&&""===d.val()?a.rules("remove","required"):a.rules("add",{required:!0,messages:{required:"Please select a valid diagnosis"}})}b.rules("add",
{isValidDiagonisedDate:!0,messages:{isValidDiagonisedDate:"Please enter a valid date"}})})}$(document).on("click","#terms_conditions",function(){$("#agree_check").click()});$(document).on("click","#signup_finish_btn",function(){handleGenderValidation();addDobValidations();addDiagnosisValidations();handleCountryZipValidation($("#UserCountry"));if($("#User").valid()&&!$("#registrationWizards .form-group").hasClass("error"))$("#User").submit(),$(this).prop("disabled",!0);else return!1});
$(document).on("click","#signup_cancel_btn",function(){window.location.href="/"});$(document).on("click",".signup_video",function(){showVideoPopup(this)});