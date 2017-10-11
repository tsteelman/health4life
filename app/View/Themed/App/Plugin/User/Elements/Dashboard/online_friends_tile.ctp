<div class="col-lg-3 online_friends">
    <div class="dashboard_header">Friends</div>
    <div class="search_bar">
        <div><input placeholder="Search friends" type="text" class="form-control" id="online_friend_search_key" onkeypress="return noenter()"></div>        
    </div>
    <?php echo $this->element('Dashboard/online_friends') ?>
</div>

<script>
    $(document).on('focus', '#online_friend_search_key', function() {
        if ($(this).val() == "") {
            $(this).val(' ');
        }
    });
    
    $(document).on('focusout', '#online_friend_search_key', function() {
        if ($(this).val() == " ") {
            $(this).val('');
        }
    });

    var my_friends_json = <?php echo $onlineFriendsJson; ?>;
    $(document).on('keyup', '#online_friend_search_key', function(event) { 
        if ($(this).val() == "") {
            $(this).val(' ');
        }
        if (my_friends_json.length > 0) {
            var search = $(this).val();
            search = $.trim(search);
            search = search.toLowerCase();
            $(".friends_list_dashboard, #no_result_found").addClass('hidden');
            $.each(my_friends_json, function(i, v) {
                var name = (v.friend_name).toLowerCase();
                if (name.search(search) !== -1) {
                    $("#" + v.friend_id).removeClass("hidden");
                } else {
                    $("#no_result_found").removeClass('hidden');
                }
            });
        } else {
            $("#no_result_found").show();
            $("#no_result_found").removeClass('hidden');
        }
    });
    function noenter() {
        return !(window.event && window.event.keyCode == 13);
    }
</script>