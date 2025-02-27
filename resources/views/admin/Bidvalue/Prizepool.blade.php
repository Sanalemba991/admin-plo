@extends('admin.layout.master')
@section('title')
PrizePool
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
          <p class="card-title"><i class="las la-certificate"></i> Create PrizePool</p>
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
 <form class="create_brand" method="post" action="{{ route('create.pool.new') }}" enctype="multipart/form-data" data-parsley-validate autocomplete="off">
      @csrf
     <input type="hidden" name="league_id" value="{{$league_id}}"/>
    <div id="prizeRows">
        <!-- Initial row for first place -->
        <div class="prizeRow">
         
            <input type="number" class="form-control" name="rankFrom[]" placeholder="Rank from">
              <span>to</span>
            <input type="number" class="form-control" name="rankTo[]" placeholder="Rank to">
                       <span> Prize</span>  
            <input type="number" class="form-control" name="prizeAmount[]" placeholder="Prize amount">
            
        </div>
    </div>
    
    <button type="button" class="btn btn-orange float-right border-0 submit_btn" id="addRowBtn">Add</button>
    <button type="submit" class="btn btn-orange float-right border-0 submit_btn">Submit</button>
</form>

<script>
    document.getElementById("addRowBtn").addEventListener("click", function(){
        var prizeRows = document.getElementById("prizeRows");
        var newRow = document.createElement("div");
        newRow.classList.add("prizeRow");
        newRow.innerHTML = `
         <label>Other Prize<span class="text-danger required-sign">*</span></label>
             <input type="number" class="form-control" name="rankFrom[]" placeholder="Rank from">
               <span>to</span>
            <input type="number" class="form-control" name="rankTo[]" placeholder="Rank to">
                 <span> Prize</span>  
            <input type="number" class="form-control" name="prizeAmount[]" placeholder="Prize amount">
             <button type="button" class="deleteRowBtn">Delete</button>
        `;
        prizeRows.appendChild(newRow);
        attachDeleteRowListener(newRow);
    });

    function attachDeleteRowListener(row) {
        row.querySelector('.deleteRowBtn').addEventListener('click', function() {
            row.remove();
        });
    }

    // Attach delete button listeners to existing rows
    document.querySelectorAll('.deleteRowBtn').forEach(function(btn) {
        attachDeleteRowListener(btn.parentElement);
    });
</script>
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
            
                <th> LEAGUE ID</th>
                <th>RANK FROM</th>
               <th>RANK TO</th>
                <th>PRIZE AMOUNT</th>
                 
                
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($brands as $key =>$result)
            <tr>
               
              <td><span class="font-weight-bold">{{$result->league_id}} </span></td>
              <td><span class="font-weight-bold">Rank {{$result->rank_from }} </span></td>
              <td><span class="font-weight-bold">Rank {{$result->rank_to }}</span></td>
               <td><span class="font-weight-bold">{{$result->prize_amount }} ₹ </span></td>
               
              <td>
                  <button type="button" data-id="{{$result->id}}" data-toggle
                
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
<script src="{{URL::asset('admin-assets/css/custom/js/bidvalue/cleague.js')}}"></script>
@endsection
