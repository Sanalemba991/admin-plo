@extends('admin.layout.master')
@section('title')
Leagues
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
          <p class="card-title"><i class="las la-certificate"></i> Create Leagues Match</p>
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
    <form class="create_brand" method="post" action="{{ route('create.league.new') }}" enctype="multipart/form-data" data-parsley-validate autocomplete="off">
        @csrf
        <div class="form-group">
            <label>Select Game <span class="text-danger required-sign">*</span></label>
            <select class="form-control" id="game_name" name="game_name" required>
                <option value="1">Ludo</option>
                
               
            </select>
        </div>
        <div class="form-group">
            <label>Entry Fee <span class="text-danger required-sign">*</span></label>
            <input type="number" class="form-control" id="entry_fee" name="entry_fee" required />
        </div>
        <div class="form-group">
            <label>Total Spots <span class="text-danger required-sign">*</span></label>
            <input type="number" class="form-control" id="total_spots" name="total_spots" required />
        </div>
          
            <input type="number" class="form-control" id="total_chances" name="total_chances" hidden />
        
        <div class="form-group">
            <label>Start Time<span class="text-danger required-sign">*</span></label>
            <input type="datetime-local" class="form-control" id="start_time" name="start_time" required />
        </div>
          <div class="form-group">
            <label>End Time<span class="text-danger required-sign">*</span></label>
            <input type="datetime-local" class="form-control" id="end_time" name="end_time" required />
        </div>
          <div class="form-group">
            <label>Result Time<span class="text-danger required-sign">*</span></label>
            <input type="datetime-local" class="form-control" id="result_time" name="result_time" required />
        </div>
        
      
        <div class="row my-3">
            <div class="col-12">
                <button type="submit" class="btn btn-orange float-right border-0 submit_btn">Submit</button>
            </div>
        </div>
    </form>
</div>



<!-- JavaScript to hide/show fields based on game type -->


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
            
                <th> ID</th>
                <th>Game Name</th>
               <th>STATUS</th>
                <th>Entry FEE</th>
                 <th>SPOTS</th>
                  <th>JOINED </th>
               
                   <th>START TIME</th>
                    <th>END TIME</th>
                    <th>RESULT TIME</th>
                
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($brands as $key =>$result)
            <tr>
                 <td><span class="font-weight-bold">{{$result->league_id}} </span></td>
            
               @if($result->game==1)
              <td><span class="font-weight-bold">Ludo</span></td>
              @else
              <td><span class="font-weight-bold">Hand Cricket</span></td>
              @endif
               @if($result->status==0)
              <td><span class="font-weight-bold">UpComing</span></td>
              @elseif($result->status==1)
              <td><span class="font-weight-bold">Live</span></td>
              @else($result->status==1)
              <td><span class="font-weight-bold">Finished</span></td>
              @endif
              <td><span class="font-weight-bold">{{$result->entry_fee}} ₹</span></td>
              <td><span class="font-weight-bold">{{$result->total_spots }} </span></td>
              <td><span class="font-weight-bold">{{$result->joined }}</span></td>
          
               <td><span class="font-weight-bold">{{$result->start_time }} </span></td>
                <td><span class="font-weight-bold">{{$result->end_time }} </span></td>
                 <td><span class="font-weight-bold">{{$result->result_time }}</span></td>
              <td>
                  <button type="button" data-id="{{$result->league_id}}" data-toggle="tooltip" data-placement="top" title="Prize Pool"class="btn btn-icon btn-icon rounded-circle btn-danger bg-darken-4 border-0 pool_button" >
              <i class="las la-stream"></i>
              </button>
                <button type="button" data-id="{{$result->league_id}}" data-toggle="tooltip" data-placement="top" title="LeaderBoard" class="btn btn-icon btn-icon rounded-circle btn-primary bg-darken-4 border-0 leaderboard_button">
              <i class="las la-cubes"></i>
              </button>
              <button type="button" data-id="{{$result->league_id}}" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-icon btn-icon rounded-circle btn-primary bg-darken-4 border-0 edit_buuton">
               <i class="las la-highlighter"></i>
              </button>
                
              <button type="button" delete-id="{{$result->league_id}}" data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-icon btn-icon rounded-circle btn-danger bg-darken-4 border-0 delete_buuton">
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
<script src="{{URL::asset('admin-assets/css/custom/js/bidvalue/league.js')}}"></script>
@endsection
