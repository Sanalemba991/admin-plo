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
 <form class="create_brand" method="post" action="{{ route('create.superbot.new') }}" enctype="multipart/form-data" data-parsley-validate autocomplete="off">
      @csrf
     
    <div id="prizeRows">
        <!-- Initial row for first place -->
        <div class="prizeRow">
         
            <input type="text" class="form-control" name="username" placeholder="Bot Name">
            
        </div>
        
        
    </div>
    <br>
    <div id="prizeRows">
        <!-- Initial row for first place -->
        <div class="prizeRow">
         
            <input type="text" class="form-control" name="email" placeholder="Bot Email">
            
        </div>
        
        
    </div>
    <br>
    <div id="prizeRows">
        <!-- Initial row for first place -->
        <div class="prizeRow">
         
            <input type="text" class="form-control" name="phone" placeholder="Bot Phone">
            
        </div>
        
        
    </div>
    <br>
   <div id="prizeRows">
        <!-- Initial row for first place -->
        <div class="prizeRow">
         
            <input type="file" class="form-control" name="photo" placeholder="Bot Photo">
            
        </div>
        
        
    </div>
    
   
    <button type="submit" class="btn btn-orange float-right border-0 submit_btn">Submit</button>
</form>


</div>



<!-- JavaScript to hide/show fields based on game type -->


      </div>
    </div>
    <!-- /Bootstrap Validation -->
    <!-- Merged -->
      <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Player ID</th>
                    <th>Image</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Amount</th>
                    <th>WIN Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($data as $key =>$result)
                  <tr>
                    <td><span class="font-weight-bold">{{ $data->firstItem() + $key }}</span></td>
                    <td>{{ $result->playerid }}</td>
                    <td>
                        @if($result['photo'] == null)
                        <img src="https://xp.io/storage/6YUfyNd.png" width="50">
                        @else
                        <img src="{{$result->photo}}" width="50">
                       @endif
                    </td>
                    <td>{{ $result->username }}</td>
                    <td>{{ $result->useremail }}</td>
                     <td>{{ $result->userphone }}</td>
                    <td>{{ $result->totalcoin }} ₹</i></td>
                    <td>{{ $result->wincoin }} ₹</i></td>
                    <td>
                      @if($result->status == 1)
                     <div class="badge badge-light-success">Active</div>
                      @else
                     <div class="badge badge-light-danger">Blocked</div>
                      @endif
                    </td>
                    <td>
                       <a href="{{url('admin/player/view/'.Crypt::encrypt($result->playerid))}}">
                        <button type="button" data-toggle="tooltip" data-placement="top" title="View" class="btn btn-icon btn-icon rounded-circle btn-success bg-darken-4 border-0 view_buuton"><i class="las la-eye"></i>
                      </button>
                      </a>
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
            {{ $data->onEachSide(3)->links('vendor.pagination.custom') }}
            </div>
          </div>
  </div>

@endsection
@section('js')
<!-- link custom js link here -->
<script src="{{URL::asset('admin-assets/css/custom/js/bidvalue/cleague.js')}}"></script>
@endsection
