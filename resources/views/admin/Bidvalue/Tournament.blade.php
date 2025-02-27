@extends('admin.layout.master')
@section('title')
Tournament
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
            <p class="card-title"><i class="las la-certificate"></i> Create Tournament</p>
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
                  <label>Title <span class="text-danger required-sign">*</span></label>
                  <input type="text" class="form-control" id="title" name="title" required />
               </div>
               <div class="form-group">
                  <label>Entry Fee <span class="text-danger required-sign">*</span></label>
                  <input type="number" class="form-control" id="entry_fee" name="entry_fee" required />
               </div>
               <div class="form-group">
                  <label>Total Players <span class="text-danger required-sign">*</span></label>
                  <input type="number" class="form-control" id="total_players" name="total_players" required />
               </div>
               <div class="form-group">
                  <label>Start Time <span class="text-danger required-sign">*</span></label>
                  <input type="datetime-local" class="form-control" id="start_time" name="start_time" required />
               </div>
               <div class="form-group">
                  <label>End Time <span class="text-danger required-sign">*</span></label>
                  <input type="datetime-local" class="form-control" id="end_time" name="end_time" required />
               </div>
               <div class="form-group">
                  <label>Result Time <span class="text-danger required-sign">*</span></label>
                  <input type="datetime-local" class="form-control" id="result_time" name="result_time" required />
               </div>
               <div class="form-group">
                  <label>Prize Distribution <span class="text-danger required-sign">*</span></label>
                  <div id="prize-container"></div>
                  <button type="button" class="btn btn-secondary mt-2" id="add-prize">Add Prize</button>
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
   <!-- Merged -->
   <div class="col-md-7 col-12">
      <div class="card">
         <div class="card-header">
            <p class="card-title"><i class="las la-certificate"></i> All Tournaments</p>
         </div>
         <div class="table-responsive">
            <table class="table">
               <thead>
                  <tr>
                     <th>ID</th>
                     <th>Title</th>
                     <th>Status</th>
                     <th>Entry Fee</th>
                     <th>Total Players</th>
                     <th>Total Rounds</th>
                     <th>Rounds Interval</th>
                     <th>Start Time</th>
                     <th>Prize Distribution</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($brands as $key => $result)
                  <tr>
                     <td>{{ $result->id }}</td>
                     <td>{{ $result->title }}</td>
                     <td>{{ $result->status == 0 ? 'Upcoming' : ($result->status == 1 ? 'Live' : 'Finished') }}</td>
                     <td>{{ $result->bit_amount }}</td>
                     <td>{{ $result->no_of_player }}</td>
                     <td>{{ $result->total_rounds }}</td>
                     <td>{{ $result->round_interval }}</td>
                     <td>{{ $result->start_time }}</td>
                     <td>
                        <ul>
                           @foreach(json_decode($result->prizes, true) as $prize)
                           <li>Rank {{ $prize['rank_from'] }}-{{ $prize['rank_to'] }}: {{ $prize['amount'] }}</li>
                           @endforeach
                        </ul>
                     </td>
                     <td>
                        <button type="button" data-id="{{ $result->id }}" class="btn btn-primary">View</button>
                        <button type="button" delete-id="{{ $result->id }}" class="btn btn-danger">Delete</button>
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
<script>
   let prizeCount = 0;
   
   document.getElementById('add-prize').addEventListener('click', function() {
       prizeCount++;
       const prizeContainer = document.getElementById('prize-container');
       const prizeGroup = document.createElement('div');
       prizeGroup.className = 'form-group';
       prizeGroup.innerHTML = `
           <label>Prize for Rank Range <span class="text-danger required-sign">*</span></label>
           <div class="input-group mb-2">
               <input type="number" class="form-control" name="prizes[${prizeCount}][rank_from]" placeholder="From Rank" required />
               <input type="number" class="form-control" name="prizes[${prizeCount}][rank_to]" placeholder="To Rank" required />
               <input type="number" class="form-control" name="prizes[${prizeCount}][amount]" placeholder="Prize Amount" required />
               <button type="button" class="btn btn-danger remove-prize">Remove</button>
           </div>
       `;
       prizeContainer.appendChild(prizeGroup);

       prizeGroup.querySelector('.remove-prize').addEventListener('click', function() {
           prizeContainer.removeChild(prizeGroup);
       });
   });
</script>
@endsection
