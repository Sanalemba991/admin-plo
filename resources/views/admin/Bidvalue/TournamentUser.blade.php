@extends('admin.layout.master')
@section('title')
LeaderBoard
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
          <p class="card-title"><i class="las la-certificate"></i> Add Bots</p>
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
 <form class="create_brand" method="post" action="{{ route('create.bot.new') }}" enctype="multipart/form-data" data-parsley-validate autocomplete="off">
      @csrf
     <input type="hidden" name="league_id" value="{{$league_id}}"/>
     <input type="hidden" name="bidamount" value="{{$entry_fee}}"/>
     <input type="hidden" name="isbot" value="1"/>
    <div id="prizeRows">
        <!-- Initial row for first place -->
        <div class="prizeRow">
         
            <select class="form-control" name="playerid">
                 @foreach($botUsers as $key =>$result)
                 <option value="{{ $result->playerid }}">{{ $result->username }}</option>
                 
                  @endforeach
            </select>
            
        </div>
    </div>
    
   <br>
    <button type="submit" class="btn btn-orange float-right border-0 submit_btn">Submit</button>
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
        <p class="card-title"><i class="las la-certificate"></i> All Prize Pool</p>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
               <th>Image</th>
                <th> Tournament ID</th>
                <th>Player Name</th>
               <th>IS BOT</th>
               
                 
                
             
            </tr>
          </thead>
          <tbody>
            @foreach($brands as $key =>$result)
            <tr>
                
                <td><img src="{{$result->pic_url}}" height="50" width="50"></td>
              <td><span class="font-weight-bold">{{$result->tournament_id}} </span></td>
              <td><span class="font-weight-bold">{{$result->player_name }} </span></td>
             @if($result->is_bot==1)
              <td><span class="font-weight-bold">YES</span></td>
              @else
              <td><span class="font-weight-bold">NO</span></td>
              @endif
               
               
        
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
