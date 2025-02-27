@extends('admin.layout.master')
@section('title')
 Cricket Bid Value
@endsection
@section('css')
 <!--  link custom css link here -->
@endsection
@section('content')
 <!-- BEGIN: Content-->
   <div class="row">
   <!-- Bootstrap Validation -->
    <div class="col-md-5 col-12">
      <div class="card">
        <div class="card-header">
          <p class="card-title"><i class="las la-certificate"></i> Create Contest for Cricket</p>
        </div>
            @if(session()->get('error'))
          <div class="alert alert-danger alert-dismissible ml-1 mr-1" id="notice_msg" role="alert">
              <div class="alert-body">
               <b>{{session()->get('error')}}</b>
              </div>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
               @elseif(session()->get('success'))
                  <div class="alert alert-success alert-dismissible ml-1 mr-1" id="success_msg" role="alert">
                      <div class="alert-body">
                       <b>{{session()->get('success')}}</b>
                      </div>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
              @endif
        <div class="card-body">
    <form class="create_brand" method="post" action="{{ route('create.criketvalue.new') }}" enctype="multipart/form-data" data-parsley-validate autocomplete="off">
        @csrf
        <div class="form-group">
            <label>Game Type <span class="text-danger required-sign">*</span></label>
            <select class="form-control" id="game_type" name="game_type" required>
                <option value="1">Head To Head</option>
               
            </select>
        </div>
        <div class="form-group">
            <label>Entry Fee <span class="text-danger required-sign">*</span></label>
            <input type="number" class="form-control" id="entry_fee" name="entry_fee" required />
        </div>
         <div class="form-group">
                <label>1st Prize <span class="text-danger required-sign">*</span></label>
                <input type="number" class="form-control" id="pos1_prize" name="pos1_prize" required />
            </div>
        <div id="tournament_fields">
           
            <div class="form-group">
                <label>2nd Prize <span class="text-danger required-sign">*</span></label>
                <input type="number" class="form-control" id="pos2_prize" name="pos2_prize" />
            </div>
            <div class="form-group">
                <label>3rd Prize <span class="text-danger required-sign">*</span></label>
                <input type="number" class="form-control" id="pos3_prize" name="pos3_prize" />
            </div>
            <div class="form-group">
                <label>Total Number of Players <span class="text-danger required-sign">*</span></label>
                <input type="number" class="form-control" id="total_no_of_players" name="total_no_of_players" />
            </div>
        </div>
        <div class="row my-3">
            <div class="col-12">
                <button type="submit" class="btn btn-orange float-right border-0 submit_btn">Submit</button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const gameTypeSelect = document.getElementById("game_type");
    const tournamentFields = document.getElementById("tournament_fields");

    const toggleTournamentFields = () => {
        if (gameTypeSelect.value == "1") {
            tournamentFields.style.display = "none"; // Hide tournament fields when game type is Head to Head
        } else {
            tournamentFields.style.display = "block"; // Show tournament fields when game type is Tournament
        }
    };

    // Initial toggle based on the default selected value
    toggleTournamentFields();

    // Add change event listener to the select element
    gameTypeSelect.addEventListener("change", toggleTournamentFields);
});
</script>


<!-- JavaScript to hide/show fields based on game type -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const gameTypeSelect = document.getElementById("game_type");
    const secondPrizeContainer = document.getElementById("second_prize_container");
    const thirdPrizeContainer = document.getElementById("third_prize_container");

    function toggleOptionalFields() {
      if (gameTypeSelect.value === "1") { // Head to Head
        secondPrizeContainer.style.display = "none";
        thirdPrizeContainer.style.display = "none";
      } else { // Tournament
        secondPrizeContainer.style.display = "block";
        thirdPrizeContainer.style.display = "block";
      }
    }

    // Initialize the field visibility based on the current game type
    toggleOptionalFields();

    // Change event listener to toggle fields
    gameTypeSelect.addEventListener("change", toggleOptionalFields);
  });
</script>

      </div>
    </div>
    <!-- /Bootstrap Validation -->
    <!-- Merged -->
    <div class="col-md-7 col-12">
          <div class="card">
      <div class="card-header">
        <p class="card-title"><i class="las la-certificate"></i> All Bid Value</p>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              
                <th>Game Type</th>
                <th>Entry FEE</th>
                 <th>PRIZE</th>
                 
                
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($brands as $key =>$result)
            <tr>
              <td><span class="font-weight-bold">{{ $brands->firstItem() + $key }}</span></td>
               @if($result->game_type==1)
              <td><span class="font-weight-bold">Head to Head</span></td>
              @else
              <td><span class="font-weight-bold">Tournament</span></td>
              @endif
              <td><span class="font-weight-bold">{{$result->entry_fee}} ₹</span></td>
              <td><span class="font-weight-bold">{{$result->pos1_prize }} ₹</span></td>
           
              <td>
             
              <button type="button" delete-id="{{$result->id}}" data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-icon btn-icon rounded-circle btn-danger bg-darken-4 border-0 delete_buuton">
               <i class="las la-trash"></i>
              </button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="my-1">
        {{ $brands->onEachSide(3)->links('vendor.pagination.custom') }}
      </div>
    </div>
    </div>
  </div>

@endsection
@section('js')
<!-- link custom js link here -->
<script src="{{URL::asset('admin-assets/css/custom/js/bidvalue/cricketvalue.js')}}"></script>
@endsection
