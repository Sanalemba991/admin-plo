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
    <!-- Removed Add Bots Section -->
    
    <!-- Merged -->
    <div class="col-md-12 col-12">
          <div class="card">
      <div class="card-header">
        <p class="card-title"><i class="las la-certificate"></i> All Participents</p>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
                <th> RANK</th>
                <th> Tournament ID</th>
                <th>Player Name</th>
                 <th>Score</th>
                  <th>Winning</th>
            </tr>
          </thead>
          <tbody>
            @foreach($brands as $key =>$result)
            <tr>
                  <td><span class="font-weight-bold">{{ $brands->firstItem() + $key }}</span></td>
               
              <td><span class="font-weight-bold">{{$result->league_id}} </span></td>
              <td><span class="font-weight-bold">{{$result->player_name }} </span></td>
             @if($result->is_bot==1)
              <td><span class="font-weight-bold">YES</span></td>
              @else
              <td><span class="font-weight-bold">NO</span></td>
              @endif
               <td><span class="font-weight-bold">{{$result->points }}  </span></td>
                <td><span class="font-weight-bold">{{$result->chances_used }}  </span></td>
                 <td><span class="font-weight-bold">{{$result->winning }}  </span></td>
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
