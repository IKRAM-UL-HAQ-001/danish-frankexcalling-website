@extends("layouts.main")
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-11 mb-xl-0 mx-auto my-5 border w-full bg-white rounded d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom mb-5">
                <h2 class="mb-0">Quaterly Report</h2>
                <div>
                    <a href="{{ route('export.quaterly')}}" class="btn btn-light">Export</a>
                </div>
            </div>
            <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center col-12">
                <div class="card-body px-0 pb-2 px-3 col-12">
                    <div class="table-responsive p-0">
                        <table id="" class="table align-items-center mb-0 table-striped table-hover px-2">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary font-weight-bolder text-dark">User Name</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder text-dark">Exchange Name</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder text-dark">New Ids Count</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder text-dark">Total Amount</th>
                                </tr>
                            </thead>
                            <tbody id="DataTableBody">
                                @foreach ($reportDatas as $data)
                                <tr data-user-id="{{ $data['user_name'] }}" data-exchange-id="{{ $data['exchange_name'] }}">
                                    <td style="width: 25%;" class="encrypted-data">{{ $data['user_name'] }}</td>
                                    <td style="width: 25%;" class="encrypted-data">{{ $data['exchange_name'] }}</td>
                                    <td style="width: 20%;" class="encrypted-data">{{ $data['TotalNewIdCount'] }}</td>
                                    <td style="width: 20%;" class="encrypted-data">{{ number_format($data['TotalAmountFourMonths'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $reportDatas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
