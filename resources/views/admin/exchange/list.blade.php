@extends("layouts.main")
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-11 mb-xl-0 mx-auto my-5 border w-full bg-white rounded d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom mb-5">
                <h2 class="mb-0">Exchanges</h2>
                <div>
                    <button type="button" class="btn btn-light text-white bg-dark" data-bs-toggle="modal" data-bs-target="#myModal">Add Exchange</button>
                </div>
            </div>
            <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center col-12">
                <div class="card-body px-0 pb-2 px-3 col-12">
                    <div class="table-responsive p-0">
                        <table id="DataTable" class="table align-items-center mb-0 table-striped table-hover px-2">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary font-weight-bolder text-dark">Exchange Name</th>
                                    <th class="text-center text-uppercase text-secondary font-weight-bolder text-dark">Action</th>
                                </tr>
                            </thead>
                            <tbody id="DataTableBody">
                                @foreach ($Exchanges as $exchange)
                                <tr data-exchange-id="{{ $exchange->id }}">
                                    <td style="width:50%;" class="text-dark">{{ $exchange->name}}</td>
                                    <td style=";" class="d-flex flex-row">
                                        <form action="{{ route('admin.exchange.userlist') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" value="{{$exchange->id}}" name="id">
                                            <button type="submit" class="btn btn-danger btn-sm mx-2">Exchange user list</button>
                                        </form>
                                        <form method="POST" action="{{route('admin.exchange.delete')}}">
                                            @csrf 
                                            <input type="hidden" id="deleteIdInput_{{$exchange->id}}" name="id" value="{{$exchange->id}}">
                                            <button type="submit" class="btn btn-danger btn-sm mx-2">Delete</button>
                                        </form>
                                        <button type="button" class="btn btn-warning btn-sm mx-2 edit-button"
                                            data-id="{{ $exchange->id }}"
                                            data-name="{{ $exchange->name }}">
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add New Exchange Modal -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center" style="background-color: #344767; color: white;">
                <h5 class="modal-title" id="myModalLabel">Add New Exchange</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success text-white" id='success' style="display:none;"></div>
                <div class="alert alert-danger text-white" id='error' style="display:none;"></div>
                <form id="form" method="post" action="{{ route('admin.exchange.formPost') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="exchange_name" class="form-label">Name</label>
                        <input type="text" class="form-control border px-3" id="exchange_name" name="name" placeholder="Enter Exchange Name" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Exchange</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Edit Exchange Modal -->
<div class="modal fade" id="editExchangeModal" tabindex="-1" aria-labelledby="editExchangeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #344767; color: white;">
                <h5 class="modal-title" id="editExchangeModalLabel">Edit Exchange</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editExchangeForm" method="post" action="{{ route('admin.exchange.update') }}">
                    @csrf
                    <input type="hidden" id="exchangeId" name="id">
                    <div class="mb-3">
                        <label for="editExchangeName" class="form-label">Exchange Name</label>
                        <input type="text" class="form-control" id="editExchangeName" name="name" placeholder="Enter Exchange Name" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    const editButtons = document.querySelectorAll('.edit-button');
    const editModal = new bootstrap.Modal(document.getElementById('editExchangeModal'));

    // Handle click event for Edit buttons
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Retrieve the exchange ID and name
            const exchangeId = button.getAttribute('data-id');
            const exchangeName = button.getAttribute('data-name'); // decryptData(button.getAttribute('data-name'));

            // Set values in the modal inputs
            document.getElementById('exchangeId').value = exchangeId;
            document.getElementById('editExchangeName').value = exchangeName;

            // Show the modal
            editModal.show();
        });
    });
    $('#editExchangeForm').on('submit', function(e) {
        e.preventDefault();

        try {
            // Encrypt the exchange name
            const exchangeId = $('#exchangeId').val();
            // const exchangeName = encryptData($('#editExchangeName').val());

            // Set encrypted value back to the form field
            // $('#editExchangeName').val(exchangeName);

            // Submit the form
            this.submit();
        } catch (error) {
            console.error("Error encrypting data:", error);
            alert("Failed to encrypt data. Please try again.");
        }
    });
});


    $('#form').on('submit', function(e) {
        e.preventDefault();

        // $('#exchange_name').val( encryptData($('#exchange_name').val())); // Use encryptData function
        this.submit();
    });

</script>

@endsection
