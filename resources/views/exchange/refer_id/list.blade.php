@extends("layouts.main")
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-11 mb-xl-0 mx-auto my-5 border w-full bg-white rounded d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom mb-5">
                <h2 class="mb-0">Refer  ID</h2>
                <div>
                    {{-- <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exportModal"> Show Report </button> --}}
                </div> 
            </div>
            <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center col-12">
                <div class="card-body px-0 pb-2 px-3 col-12">
                    <div class="table-responsive p-0">
                        <table  class="table align-items-center mb-0 table-striped table-hover px-2">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary font-weight-bolder text-dark">Name</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder text-dark">Phone Number</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder text-dark">Feedback</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder text-dark">Amount</th>
                                    <th class="text-center text-uppercase text-secondary font-weight-bolder text-dark">Date and Time</th>
                                </tr>
                            </thead>
                            <tbody id="DataTableBody">
                                @foreach ($ReferIds as $referId)
                                <tr data-user-id="a" data-exchange-id="a">
                                    <td style="width: 45%;" class="encrypted-data">{{$referId->name}}</td>
                                    <td style="width: 45%;" class="encrypted-data">{{$referId->phone->phone_number}}</td>
                                    <td style="width: 45%;" class="encrypted-data">{{$referId->feedback}}</td>
                                    <td style="width: 45%;" class="encrypted-data">{{$referId->amount}}</td>
                                    <td style="width: 45%;">{{$referId->created_at}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$ReferIds->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center " style="background:#acc301;">
                <h5 class="modal-title" id="exportModalLabel" style="color:white">Export Walk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <form id="reportForm" action="{{ route('export.referId') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" id="id" value="{{session('user_id')}}">
                    <div class="mb-3">
                        <label for="sdate" class="form-label">Start Date:</label>
                        <input type="date" class="form-control border px-3" id="sdate" name="start_date"
                            required value="{{ \Carbon\Carbon::today()->toDateString() }}">
                    </div>
                    <div class="mb-3">
                        <label for="edate" class="form-label">End Date:</label>
                        <input type="date" class="form-control border px-3" id="edate" name="end_date"
                            required value="{{ \Carbon\Carbon::today()->toDateString() }}">
                    </div>
                    <button type="submit" class="btn" style="background:#acc301;" id="generateReportBtn">
                        <span id="btnText">Generate File</span>
                        <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status"
                            aria-hidden="true"></span>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
