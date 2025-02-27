
$(document).ready(function(){
    "use strict";
    $(".edit_button").each(function(){
        $(this).on('click',function(){
            var edit_id = $(this).data("id");
            //now ajax request
            $.ajax({
                type : "GET",
                url: "coin/edit/" + edit_id,
                data: {
                    _token: $("body").data("token"),
                },
                success : function(response){
                    console.log(response);
                    var brand_id = response.data[0].id;
                    var game_type = response.data[0].game_type;
                    var entry_fee = response.data[0].entry_fee;
                    var first_prize = response.data[0].first_prize;
                    var second_prize = response.data[0].second_prize;

                    var domain = window.location.protocol + "//" + window.location.host;

                    //now append value
                    $("#game_type").val(game_type);
                    $("#entry_fee").val(entry_fee);
                    $("#first_prize").val(first_prize);
                    $("#second_prize").val(second_prize);
                    $(".submit_btn").html("Update");
                    $(".create_brand").attr("action","coin/update/"+brand_id);
                }
            });
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
                  url: "coupon/delete/" + delete_id,
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
                    if (ajax.status == 404) {

                      swal({
                        title: "Opps !",
                        text: "Something Went Wrong Please Try Again2 !",
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
