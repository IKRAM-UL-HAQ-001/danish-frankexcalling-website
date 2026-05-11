@extends("layouts.main")
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-11 mb-xl-0 mx-auto my-5 border w-full bg-white rounded d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom mb-5">
                <h2 class="mb-0">No Of Calls</h2>
            </div>
            <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center col-12">
                <div class="card-body px-0 pb-2 px-3 col-12">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0 table-striped table-hover px-2">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary font-weight-bolder text-dark">User Name</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder text-dark">Exchange</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder text-dark">Phone</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder text-dark">Date and Time</th>
                                    <th class="text-center text-uppercase text-secondary font-weight-bolder text-dark">Action</th>
                                </tr>
                            </thead>
                            <tbody id="DataTableBody">
                                @foreach ($NoOfCalls as $NoOfCall)
                                <tr data-user-id="a" data-exchange-id="a">
                                    <td style="width: 45%;" class="encrypted-data">{{$NoOfCall->user->name}}</td>
                                    <td style="width: 45%;" class="encrypted-data">{{$NoOfCall->exchange->name}}</td>
                                    <td style="width: 45%;" class="encrypted-data">{{$NoOfCall->phone_number}}</td>
                                    <td >{{$NoOfCall->updated_at}}</td>
                                    <td style="width: 10%; text-align: center;">
                                        <form method="POST" action="{{route('admin.no_of_call.delete')}}">
                                            @csrf 
                                            <input type="hidden" id="deleteIdInput" name="id" value="{{$NoOfCall->id}}">
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$NoOfCalls->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
