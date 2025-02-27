@extends('admin.layout.master')
@section('title')
 Bid Value
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
          <p class="card-title"><i class="las la-certificate"></i> Create Contest for Ludo</p>
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
    <form class="create_brand" method="post" action="{{ route('create.bidvalue.new') }}" enctype="multipart/form-data" data-parsley-validate autocomplete="off">
    @csrf
    <div class="form-group">
        <label>Game Type <span class="text-danger required-sign">*</span></label>
        <select class="form-control" id="game_type" name="game_type" required>
            <option value="1">1 vs 1</option>
            <option value="3">1 vs 3</option>
                   </select>
    </div>
    <div class="form-group">
        <label>Entry Fee <span class="text-danger required-sign">*</span></label>
        <input type="number" class="form-control" id="entry_fee" name="entry_fee" required />
    </div>
    <div class="form-group">
        <label>1st prize<span class="text-danger required-sign">*</span></label>
        <input type="number" class="form-control" id="first_prize" name="first_prize" required />
    </div>
    <div class="form-group">
        <label>2nd Prize <span class="text-danger required-sign">*</span></label>
        <input type="number" class="form-control" id="second_prize" name="second_prize" required />
    </div>
    <div class="row my-3">
        <div class="col-12">
            <button type="submit" class="btn btn-orange float-right border-0 submit_btn">Submit</button>
        </div>
    </div>
</form>


        </div>
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
                 <th>1ST PRIZE</th>
                  <th>2ND PRIZE</th>
                
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($brands as $key =>$result)
            <tr>
              <td><span class="font-weight-bold">{{ $brands->firstItem() + $key }}</span></td>
               @if($result->game_type==1)
              <td><span class="font-weight-bold">1 VS 1</span></td>
              @else
              <td><span class="font-weight-bold">1 VS 3</span></td>
              @endif
              <td><span class="font-weight-bold">{{$result->entry_fee}} ₹</span></td>
              <td><span class="font-weight-bold">{{$result->first_prize }} ₹</span></td>
              <td><span class="font-weight-bold">{{$result->second_prize }} ₹</span></td>
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
<script src="{{URL::asset('admin-assets/css/custom/js/bidvalue/bidvalue.js')}}"></script>
@endsection
