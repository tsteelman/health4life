var myTeam = Array;


$(document).ready(function() {
    // create team user type selection
    $(document).on('click', '.userType', function() {
        
        if ($('#frmCreateTeam').length > 0) {
            // init photo uploader
            createUploader();
        }
        if ($(this).data("usertype") != "self") {
            /*
             * Show the search user popup,
             * select a user and set the details
             */
            
        } else {
            setSelectedPatient($(this).data());
        }
    });
    
    
    
     
    $(document).on('click', '.cancelUser', function() {
        resetUserSelection();
    });    
    
    $(document).on('click', '.skipInvite', function() {
        if($(this).data("team_url") != "")
        window.location.href = $(this).data("team_url");
    });   
    
    $(document).on('click', '.gotoStep3', function() {
        var userObj = getTeamDetails();
        $("#team_step3 .username").html(userObj.username);
//        if(userObj.hasuserphoto != "") {
//            $("#team_step3 .userphoto").attr("src", userObj.hasuserphoto);
//        }
        $("#team_step3 .team_userid").val(userObj.userid);
        $("#team_name").val("Team " + userObj.username);
        
        $(".teamstep").hide();
        $("#team_step3").show();    
    });   
    
    
    $(document).on('click', '.createTeam', function() {
		$('#team_name_error').hide();
        createTeam();
    });
    
});

    function createTeam() {
        if(parseInt($("#frmCreateTeam .team_userid").val()) > 0 && 
           $("#frmCreateTeam #team_name").val() != "") {
            $(this).attr("disabled", "disabled");
            $(".cancelUser").attr("disabled", "disabled");   
            $(this).html("<img width='20px' src='"+app.site_url + 'theme/App/img/loading.gif'+"' />")

            $.ajax({
                    dataType: 'json',
                    type: 'POST',
                    url: '/myteam/create/finish',
                    data: $("#frmCreateTeam").serialize(),
                    success: function(data) {
                        if(data.success) {
                            myTeam = data.team;
                            if(data.redirect_url === "invite") {
                                $(".teamstep").hide();
                                $("#team_step4").show();
                                $('.skipInvite').data("team_url", myTeam.team_url);
                                $("#team_step4 .username").html(myTeam.name);
                            } else {
                                window.location.href = data.redirect_url;
                            }
                        } else {
                            alert(data.message);
                            $(".createTeam").removeAttr("disabled");                        
                            $(".cancelUser").removeAttr("disabled"); 
                            $(".createTeam").html("Create");
                        }

                    }
            });   
        } else {
			$('#team_name_error').show();
		}
    }

    function initTeamDetails(obj) {
        var selUsername = "";
        if(obj.userid == app.loggedInUserId) {
            selUsername = "Me";
        } else {
            selUsername = obj.username;
        }
        $("#team_username").html(selUsername);
//        $("#team_userphoto").attr("src", obj.userphoto);        
        $(".teamstep").hide();
        $("#team_step2").show();

    }
    
    function resetUserSelection() {
        $(".teamstep").hide();
        $("#team_step1").show();
        $("#team_step3 .userphoto").attr("src", $("#team_step3 .userphoto").data('default_image'));
        
    }
       
    function setSelectedPatient(userObj){
            setTeamDetails(userObj);
            initTeamDetails(userObj);
    }

    function setTeamDetails(userObj) {        
        myTeam['userid'] = userObj.userid;
        myTeam['username'] = userObj.username;
        myTeam['userphoto'] = userObj.userphoto;
        myTeam['hasuserphoto'] = userObj.hasuserphoto;
    }
    
    function getTeamDetails(){
        return myTeam;
    }