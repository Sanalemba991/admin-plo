@extends('admin.layout.master')
@section('title')
 Admin Dashboard
@endsection
@section('css')
 <!--  link custom css link here -->
 <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

 <link rel="stylesheet" type="text/css" href="{{URL::asset('admin-assets/css/plugins/charts/chart-apex.min.css')}}">
 <link rel="stylesheet" type="text/css" href="{{URL::asset('admin-assets/vendors/css/charts/apexcharts.css')}}">
@endsection
@section('content')
 <!-- Dashboard Ecommerce Starts -->
<section id="dashboard-analytics">
   <!-- Line Chart Card -->
  <div class="row">
    <div class="col-lg-4 col-sm-6 col-12">
      <div class="card">
        <div class="card-header align-items-start pb-0">
          <div>
            <h2 class="font-weight-bolder">
            @if($Todays >= 1000)
            {{$Todays/1000}} K
            @else
            {{$Todays}}
            @endif
            </h2>
            <p class="card-text mb-2">Today's Register Player</p>
          </div>
          <div class="avatar bg-success p-50">
            <div class="avatar-content">
             <i class="las la-user-friends font-medium-5"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-sm-6 col-12">
        <div class="card">
          <div class="card-header align-items-start pb-0">
            <div>
              <h2 class="font-weight-bolder">
                @if($totalUser > 1000)
                {{$totalUser/1000}} K
                @else
                {{$totalUser}}
                @endif
              </h2>
              <p class="card-text mb-2">Total Register Player</p>
            </div>
            <div class="avatar bg-warning p-50">
              <div class="avatar-content">
               <i class="las la-users font-medium-5"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="col-lg-4 col-sm-6 col-12">
      <div class="card">
        <div class="card-header align-items-start pb-0">
          <div>
            <h2 class="font-weight-bolder">
                @if($OwnerAmount > 1000)
                {{$OwnerAmount/1000}} K ₹</i>
                @else
                {{$OwnerAmount}} ₹</i>
                @endif
            </h2>
            <p class="card-text mb-2">Total Deposit</p>
          </div>
          <div class="avatar bg-danger p-50">
            <div class="avatar-content">
             <i class="las la-wallet font-medium-5"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    
      <div class="col-lg-4 col-sm-6 col-12">
      <div class="card">
        <div class="card-header align-items-start pb-0">
          <div>
            <h2 class="font-weight-bolder">
                @if($WalletAmount > 1000)
               ₹ {{$WalletAmount/1000}} K </i>
                @else
               ₹ {{$WalletAmount}} </i>
                @endif
            </h2>
            <p class="card-text mb-2">Bonus Given</p>
          </div>
          <div class="avatar bg-danger p-50">
            <div class="avatar-content">
             <i class="las la-wallet font-medium-5"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    
      <div class="col-lg-4 col-sm-6 col-12">
      <div class="card">
        <div class="card-header align-items-start pb-0">
          <div>
            <h2 class="font-weight-bolder">
                @if($owneranl['Withdrawable'] > 1000)
                ₹{{$owneranl['Withdrawable']/1000}} K </i>
                @else
                ₹{{$owneranl['Withdrawable']}} </i>
                @endif
            </h2>
            <p class="card-text mb-2">Total Withdrawl Given</p>
          </div>
          <div class="avatar bg-danger p-50">
            <div class="avatar-content">
             <i class="las la-wallet font-medium-5"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
      <div class="col-lg-4 col-sm-6 col-12">
      <div class="card">
        <div class="card-header align-items-start pb-0">
          <div>
            <h2 class="font-weight-bolder">
                @if($OwnerAmount-$owneranl['Withdrawable'] > 1000)
                ₹{{$OwnerAmount-$owneranl['Withdrawable']/1000}} K </i>
                @else
                ₹{{$OwnerAmount-$owneranl['Withdrawable']}} </i>
                @endif
            </h2>
            <p class="card-text mb-2">Profit Till Now</p>
          </div>
          <div class="avatar bg-danger p-50">
            <div class="avatar-content">
             <i class="las la-wallet font-medium-5"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  
  
  <!--/ Line Chart Card -->

  <!-- Line Area Chart Card -->
  <div class="row">
   <div class="col-lg-3 col-sm-6 col-12">
  <div class="card">
    <div class="card-header flex-column align-items-start pb-0">
      <div class="avatar bg-primary p-50 m-0">
        <div class="avatar-content">
          <i class="las la-rupee-sign font-medium-5"></i>
        </div>
      </div>
      <h2 class="font-weight-bolder mt-1">
        @if($WinningAmount > 1000)
          {{$WinningAmount/1000}} K
        @else
          {{$WinningAmount}}
        @endif
        ₹
      </h2>
      <p class="card-text">Total Winning Amount</p>
    </div>
    <div id="column-chart-1"></div>
  </div>
</div>

<div class="col-lg-3 col-sm-6 col-12">
  <div class="card">
    <div class="card-header flex-column align-items-start pb-0">
      <div class="avatar bg-success p-50 m-0">
        <div class="avatar-content">
          <i class="las la-rupee-sign font-medium-5"></i>
        </div>
      </div>
      <h2 class="font-weight-bolder mt-1">
        @if($totalUser-$TotalSentMoney > 1000)
          {{$totalUser-$TotalSentMoney/1000}} K
        @else
          {{$totalUser-$TotalSentMoney}}
        @endif
      </h2>
      <p class="card-text">Total User by Referral</p>
    </div>
    <div id="column-chart-2"></div>
  </div>
</div>

<div class="col-lg-3 col-sm-6 col-12">
  <div class="card">
    <div class="card-header flex-column align-items-start pb-0">
      <div class="avatar bg-warning p-50 m-0">
        <div class="avatar-content">
          <i class="las la-exchange-alt font-medium-5"></i>
        </div>
      </div>
      <h2 class="font-weight-bolder mt-1">
        @if($AllTransaction > 1000)
          {{$AllTransaction/1000}} K
        @else
          {{$AllTransaction}}
        @endif
      </h2>
      <p class="card-text">Total Transaction</p>
    </div>
    <div id="column-chart-3"></div>
  </div>
</div>

<div class="col-lg-3 col-sm-6 col-12">
  <div class="card">
    <div class="card-header flex-column align-items-start pb-0">
      <div class="avatar bg-success p-50 m-0">
        <div class="avatar-content">
          <i class="las la-paper-plane font-medium-5"></i>
        </div>
      </div>
      <h2 class="font-weight-bolder mt-1">
        @if($totalUser-$TotalApprovedKyc > 1000)
          {{$totalUser-$TotalApprovedKyc/1000}} K
        @else
          {{$totalUser-$TotalApprovedKyc}}
        @endif
      </h2>
      <p class="card-text">Total Filled KYC</p>
    </div>
    <div id="column-chart-4"></div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Chart 1: Total Winning Amount
    var options1 = {
      chart: { type: 'bar', height: 350 },
      series: [{ name: 'Winning Amount', data: [71, 120, 250, 300, 420] }],
      xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May'] },
      colors: ['#007bff'] // Primary color
    };
    new ApexCharts(document.querySelector("#column-chart-1"), options1).render();

    // Chart 2: Total User by Referral
    var options2 = {
      chart: { type: 'bar', height: 350 },
      series: [{ name: 'Users by Referral', data: [150, 180, 270, 330, 490] }],
      xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May'] },
      colors: ['#28a745'] // Success color
    };
    new ApexCharts(document.querySelector("#column-chart-2"), options2).render();

    // Chart 3: Total Transaction
    var options3 = {
      chart: { type: 'bar', height: 350 },
      series: [{ name: 'Total Transactions', data: [60, 130, 200, 400, 500] }],
      xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May'] },
      colors: ['#ffc107'] // Warning color
    };
    new ApexCharts(document.querySelector("#column-chart-3"), options3).render();

    // Chart 4: Total Filled KYC
    var options4 = {
      chart: { type: 'bar', height: 350 },
      series: [{ name: 'Filled KYC', data: [90, 160, 250, 340, 410] }],
      xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May'] },
      colors: ['#17a2b8'] // Info color
    };
    new ApexCharts(document.querySelector("#column-chart-4"), options4).render();
  });
</script>

  </div>
  <!--/ Line Area Chart Card -->
    <!-- Line Chart Card -->
  <div class="row">
    <div class="col-lg-4 col-sm-6 col-12">
      <div class="card">
        <div class="card-header align-items-start pb-0">
          <div>
            <h2 class="font-weight-bolder">
            @if($TotalPendingWithRequest > 1000)
            {{$TotalPendingWithRequest/1000}} K
            @else
            {{$TotalPendingWithRequest}}
            @endif
            </h2>
            <p class="card-text mb-2">Pending Withdraw Request</p>
          </div>
          <div class="avatar bg-warning p-50">
            <div class="avatar-content">
             <i class="las la-hand-holding-usd font-medium-5"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-sm-6 col-12">
      <div class="card">
        <div class="card-header align-items-start pb-0">
          <div>
            <h2 class="font-weight-bolder">
            @if($TotalApprovedWithRequest > 1000)
            {{$TotalApprovedWithRequest/1000}} K
            @else
            {{$TotalApprovedWithRequest}}
            @endif
            </h2>
            <p class="card-text mb-2">Approved Withdraw Request</p>
          </div>
          <div class="avatar bg-success p-50">
            <div class="avatar-content">
             <i class="las la-check-circle font-medium-5"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-sm-6 col-12">
      <div class="card">
        <div class="card-header align-items-start pb-0">
          <div>
            <h2 class="font-weight-bolder">
            @if($TotalRejectWithRequest > 1000)
            {{$TotalRejectWithRequest/1000}} K
            @else
            {{$TotalRejectWithRequest}}
            @endif
            </h2>
            <p class="card-text mb-2">Reject Withdraw Request</p>
          </div>
          <div class="avatar bg-danger p-50">
            <div class="avatar-content">
             <i class="las la-times-circle font-medium-5"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Line Chart Card -->



  <div class="row match-height">
    <!-- Timeline Card -->
    <div class="col-lg-4 col-12">
      <div class="card">
      <div class="card-header">
        <p class="card-title"><i class="las la-users"></i> Recent New Registration</p>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Player ID</th>
              <th>Name</th>
              <th>Images</th>
            </tr>
          </thead>
          <tbody>
            @foreach($Userdata as $key =>$result)
            <tr>
              <td><span class="font-weight-bold">{{ $Userdata->firstItem() + $key }}</span></td>
              <td>{{$result->playerid}}</td>
              <td>{{$result->username}}</td>
              <td>
                @if($result['photo'] == null)
                <img src="https://xp.io/storage/6YUfyNd.png" width="50">
                @else
                <img src="{{$result->photo}}" width="50">
               @endif
                </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    </div>
    <!--/ Timeline Card -->
    <!-- Timeline Card -->
    <div class="col-lg-4 col-12">
      <div class="card">
      <div class="card-header">
         <p class="card-title"><i class="las la-exchange-alt"></i> Recent Transaction</p>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>User ID</th>
              <th>Amount</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($Transaction as $key =>$result)
            <tr>
              <td><span class="font-weight-bold">{{ $Transaction->firstItem() + $key }}</span></td>
              <td>{{$result->userid}}</td>
              <td><b>{{$result->amount}} ₹</b></td>
              <td>
                @if($result->status == "success")
              <div class="badge badge-light-success">Success</div>
                @else
              <div class="badge badge-light-danger">Failed</div>
                @endif

              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    </div>
    <!--/ Timeline Card -->
    <!-- Timeline Card -->
    <div class="col-lg-4 col-12">
      <div class="card">
      <div class="card-header">
        <p class="card-title"><i class="las la-hand-holding-usd"></i> Recent Withdraw</p>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>User ID</th>
              <th>Amount</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($Withdraw as $key =>$result)
            <tr>
              <td><span class="font-weight-bold">{{ $Withdraw->firstItem() + $key }}</span></td>
              <td>{{$result->userid}}</td>
              <td><b>{{$result->amount}} ₹</b></td>
              <td>
                @if($result->status == 1)
              <div class="badge badge-light-success">Success</div>
                @else
              <div class="badge badge-light-danger">Failed</div>
                @endif

              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    </div>
    <!--/ Timeline Card -->



    <!--/ Timeline Card -->
  </div>

</section>
<!-- Dashboard Ecommerce ends -->
@endsection
@section('js')
 <script src="{{URL::asset('admin-assets/vendors/js/charts/apexcharts.min.js')}}"></script>
  <script src="{{URL::asset('admin-assets/js/scripts/cards/card-statistics.min.js')}}"></script>
<!-- link custom js link here -->
@endsection
