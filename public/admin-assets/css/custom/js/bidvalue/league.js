
$(document).ready(function(){
    "use strict";
    $(".edit_buuton").each(function(){
        $(this).on('click',function(){
            var edit_id = $(this).data("id");
            //now ajax request
            $.ajax({
                type : "GET",
                url: "coin/edit_league/" + edit_id,
                data: {
                    _token: $("body").data("token"),
                },
                success : function(response){
                    console.log(response);
                    var game_name = response.data[0].game_name;
                    var entry_fee = response.data[0].entry_fee;
                    var total_spots = response.data[0].total_spots;
                    var total_chances = response.data[0].total_chances;
                    var start_time = response.data[0].start_time;
                    var end_time = response.data[0].end_time;
                    var result_time=response.data[0].result_time;
                    var domain = window.location.protocol + "//" + window.location.host;

                    //now append value
                    $("#game_name").val(game_name);
                    $("#entry_fee").val(entry_fee);
                    $("#total_spots").val(total_spots);
                    $("#total_chances").val(total_chances);
                    $("#start_time").val(start_time);
                    $("#end_time").val(end_time);
                    $(".submit_btn").html("Update");
                    $(".create_brand").attr("action","coin/update_league/"+brand_id);
                }
            });
        });
    });
});


$(document).ready(function(){
    "use strict";
    $(".pool_button").each(function(){
        $(this).on('click',function(){
             var league_id = $(this).data("id");
           var domain = window.location.protocol + "//" + window.location.host;
           window.open(domain+"/admin/bid/prizepool?league_id="+league_id);
        });
    });
});
$(document).ready(function(){
    "use strict";
    $(".leaderboard_button").each(function(){
        $(this).on('click',function(){
             var league_id = $(this).data("id");
           var domain = window.location.protocol + "//" + window.location.host;
           window.open(domain+"/admin/bid/rank_user?league_id="+league_id);
        });
    });
});


//now delete method

$(document).ready(function(){
	"use strict";
	$(".delete_buuton").each(function(){
		$(this).on('click',function(){
		  var delete_id = $(this).attr("delete-id");
		    swal({
              title: "Are you sure?",
              text: "Do You Really Want To Delete These Records ?",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#28C76F",
              cancelButtonColor: '#ac0e0d',
              confirmButtonText: "Yes, Delete it!",
              cancelButtonText: "No, Sorry!",
              closeOnConfirm: false,
              closeOnCancel: false
            },
            function(isConfirm) {
              if (isConfirm) {
                //now ajax Request
                $.ajax({
                  type: "post",
                  url: "coin/delete_league/" + delete_id,
                  data: {
                    _token: $("body").attr("token"),
                  },
                  success: function(response) {
                    swal(
                      'Congratulations!',
                      'Bid Value Deleted Succesfully !',
                      'success'
                    );
                    $(".sa-confirm-button-container").on('click', function() {
                      location.reload();
                    });
                  },

                  error: function(ajax) {
                    if (ajax.status == 500) {

                      swal({
                        title: " Opps !",
                        text: "Something Went Wrong Please Try Again !",
                        type: "error",
                        showCancelButton: true,
                        confirmButtonText: 'Ok',
                      });

                      $(".sa-confirm-button-container").on('click', function() {
                        location.reload();
                      });
                    }
                    if (ajax.status == 404) {

                      swal({
                        title: "Opps !",
                        text: "Something Went Wrong Please Try Again !",
                        type: "error",
                        showCancelButton: true,
                        confirmButtonText: 'Ok',
                      });

                      $(".sa-confirm-button-container").on('click', function() {
                        location.reload();
                      });

                    }
                  }

                });
              } else {
                swal("Cancelled", "Good ! Now You Data Is Safe !", "error");
              }
            }
          );
		});
	});
});
