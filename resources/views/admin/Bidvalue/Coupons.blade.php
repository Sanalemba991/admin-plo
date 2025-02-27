@extends('admin.layout.master')
@section('title')
Coupons
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
          <p class="card-title"><i class="las la-certificate"></i> Create Coupons</p>
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
    <form class="create_brand" method="post" action="{{ route('create.coupons.new') }}" enctype="multipart/form-data" data-parsley-validate autocomplete="off">
    @csrf
    <div class="form-group">
        <label>Coupon Code <span class="text-danger required-sign">*</span></label>
      <input type="text" class="form-control" id="coupon_id" name="coupon_id" required />
    </div>
    <div class="form-group">
        <label>Amount <span class="text-danger required-sign">*</span></label>
        <input type="number" class="form-control" id="amount" name="amount" required />
    </div>
    <div class="form-group">
        <label>No of Uses <span class="text-danger required-sign">*</span></label>
        <input type="number" class="form-control" id="uses" name="uses" required />
    </div>
    <div class="form-group">
        <label>Validity <span class="text-danger required-sign">*</span></label>
        <input type="date" class="form-control" id="validity" name="validity" required />
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
        <p class="card-title"><i class="las la-certificate"></i> All Coupons</p>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              
                <th>Coupon Code</th>
                <th>Amount</th>
                 <th>Status</th>
                 <th>Validity</th>
                  <th>Uses</th>
                 
                
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($brands as $key =>$result)
            <tr>
              <td><span class="font-weight-bold">{{ $brands->firstItem() + $key }}</span></td>
                  <td><span class="font-weight-bold">{{$result->code}} ₹</span></td>
                  <td><span class="font-weight-bold">{{$result->amount}} ₹</span></td>
               @if($result->status==0)
              <td><span class="font-weight-bold">Not used yet</span></td>
              @else
              <td><span class="font-weight-bold">Used by Player ID:{{$result->player_id}} </span></td>
              @endif
          <td><span class="font-weight-bold">{{$result->validity}}</span></td>
          <td><span class="font-weight-bold">{{$result->used}}/{{$result->total_uses}}</span></td>
              
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
<script src="{{URL::asset('admin-assets/css/custom/js/bidvalue/coupon.js')}}"></script>
@endsection
