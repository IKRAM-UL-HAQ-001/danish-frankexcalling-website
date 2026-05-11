@extends('layouts.main')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-11 mb-xl-0 mx-auto my-5 border w-full bg-white rounded d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom mb-5">
                    <h2 class="mb-0">Phone Numbers</h2>
                    <div>
                        <button type="button" class="btn btn-secondary ms-2" style="background: #344767;"
                            data-bs-toggle="modal" data-bs-target="#fileModal">Upload Excel File</button>
                        <button type="button" class="btn btn-light text-white bg-dark" data-bs-toggle="modal"
                            data-bs-target="#myModal">Add Number</button>
                    </div>
                </div>

                <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center col-12">
                    <div class="card-body px-0 pb-2 px-3 col-12">
                        @if (session('success'))
                            <div class="alert alert-success" id="success-alert">
                                {{ session('success') }}
                            </div>
                            <script>
                                setTimeout(function() {
                                    document.getElementById("success-alert").style.display = "none";
                                }, 2000);
                            </script>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger" id="error-alert">
                                {{ session('error') }}
                            </div>
                            <script>
                                setTimeout(function() {
                                    document.getElementById("error-alert").style.display = "none";
                                }, 2000);
                            </script>
                        @endif
                        <div class="w-100  d-flex justify-content-end">

                            <div class="col-md-4 mr-2">
                                <form method="POST" id="searchFormPhone" class="d-flex">
                                    @csrf
                                    <input type="text" id="tableSearchPhone" name="search"
                                        class="form-control rounded-start py-1 px-2" placeholder="Search From Phone"
                                        value="{{ request('search') }}" autocomplete="off">
                                    <button id="searchButton" type="submit" class="btn btn-primary px-3 py-3 m-0"
                                        style="background:#acc300; border-radius:0;">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                        <div class="table-responsive p-0">
                            <table  class="table align-items-center mb-0 table-striped table-hover px-2">
                                <thead>
                                    <tr>
                                        <!-- <th class="text-uppercase text-secondary font-weight-bolder text-dark">Phone Number --></th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark ps-2">User
                                            Name</th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark ps-2">Date and
                                            Time</th>
                                    </tr>
                                </thead>

                                <tbody id="DataTableBody">
                                    @foreach ($PhoneNumbers as $phoneNumber)
                                        <tr>
                                            <!-- <td class="encrypted-data">{{ $phoneNumber->phone_number }}</td> -->
                                            @if($phoneNumber->user)
                                            <td class="encrypted-data">{{ $phoneNumber->user->name ?? '' }}</td>
                                            @else
                                            <td> No user </td>
                                            @endif
                                            <td>{{ $phoneNumber->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{$PhoneNumbers->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Number Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title" id="myModalLabel" style="color:white">Add New Number</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success text-white" id='success' style="display:none;"></div>
                    <div class="alert alert-danger text-white" id='error' style="display:none;"></div>
                    <form id="form" name="formm" method="post"
                        action="{{ route('assistant.phone_number.formPost') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="editExchange" class="form-label">Users</label>
                            <select class="form-select px-3" id="editExchange" name="user_id" required>
                                <option value="" disabled selected>Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" class="encrypted-data">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control border px-3" id="phone_number" name="phone_number"
                                placeholder="Enter Phone Number" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Number</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title" id="fileModalLabel" style="color:white">Upload or Select Excel File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadForm" name="uploadFormm" method="post"
                        action="{{ route('assistant.phone_number.filePost') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Users</label>
                            <select class="form-select px-3" id="user_id" name="user_id" required>
                                <option value="" disabled selected>Select User</option>
                                @foreach ($users as $user)
                                    <option class="encrypted-data" value="{{ $user->id }}">{{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">Upload New File</label>
                            <input type="file" class="form-control border px-3" id="file" name="file">
                        </div>

                        <div class="mb-3">
                            <label for="existing_file" class="form-label">Or Select Existing File</label>
                            <select class="form-select px-3" id="existing_file" name="existing_file">
                                <option value="" disabled selected>Select Existing File</option>
                                @foreach ($uploadedFiles as $file)
                                    <option value="{{ $file->file_path }}">{{ $file->file_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script defer>
        $('#searchFormPhone').on('submit', function(event) {
            event.preventDefault(); // Prevent form submission

            const searchQuery = $('#tableSearchPhone').val().trim(); // Fetch the input value

            if (!searchQuery) {
                alert('Please enter a phone number to search.');
                return;
            }

            $.ajax({
                url: "{{ route('assistant.phone_number.search') }}", // Laravel route
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Laravel CSRF token
                    phone_number: encryptData(searchQuery) // Encrypt the phone number
                },
                success: function(response) {
                    if (response.success) {
                        const phoneNumbers = response.phoneNumbers;

                        // Clear existing table rows
                        $('#DataTableBody').empty();
                        // Check if the     response data is an array
                        if (Array.isArray(phoneNumbers) && phoneNumbers.length > 0) {
                            // Populate table with new data
                            phoneNumbers.forEach(function(phoneNumber) {
                                const userName = phoneNumber.user ? decryptData(phoneNumber.user
                                    .name) : '';
                                const phoneNumberValue = phoneNumber.phone_number ? decryptData(
                                    phoneNumber.phone_number) : '';
                                const status = phoneNumber.status || '';
                                const createdAt = phoneNumber.created_at ?
                                    new Date(phoneNumber.created_at).toLocaleString('en-US', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit',
                                        second: '2-digit',
                                        timeZone: 'UTC',
                                    }) :
                                    '';


                                const row = `
                            <tr>
                                <td>${phoneNumberValue}</td>
                                <td>${userName}</td>
                                <td>${createdAt}</td>
                            </tr>`;
                                $('#DataTableBody').append(row);
                            });
                        } else {
                            alert('No phone numbers found.');
                        }
                    } else {
                        alert(response.message || 'No data found.');
                    }
                },
                error: function(xhr) {
                    console.error('Error occurred:', xhr);
                    alert('Something went wrong. Please try again.');
                }
            });
        });
        $(document).ready(function() {
            console.log('Document is ready');
            $('.select-checkbox').prop('checked', false);

            // Highlight the row when checkbox state changes
            $('.select-checkbox').on('change', function() {
                if ($(this).is(':checked')) {
                    $(this).closest('tr').addClass('table-active');
                } else {
                    $(this).closest('tr').removeClass('table-active');
                }
            });

            // Variable to track the state of "Check All" button
            let isAllChecked = false;

            // Toggle "Check All" button functionality
            $('#checkAll').on('click', function() {
                if (isAllChecked) {
                    // Uncheck all checkboxes
                    $('.select-checkbox').prop('checked', false).closest('tr').removeClass('table-active');
                    $(this).text('Check All'); // Update button text
                } else {
                    // Check all checkboxes
                    $('.select-checkbox').prop('checked', true).closest('tr').addClass('table-active');
                    $(this).text('Uncheck All'); // Update button text
                }

                // Toggle the state
                isAllChecked = !isAllChecked;
            });

            // Form submission for adding a new phone number
            $('#form').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission for validation

                let sanitizedNumber = $('#phone_number').val().toString().trim().replace(/[^0-9]/g,
                    ''); // Remove non-numeric characters

                // Check if the number is greater than 10 digits
                if (sanitizedNumber.length > 10) {
                    if (sanitizedNumber.startsWith('91')) {
                        sanitizedNumber = sanitizedNumber.slice(2); // Remove '91' from the start
                    }
                }

                // Check if the number is exactly 10 digits
                if (sanitizedNumber.length !== 10) {
                    alert('Phone number must be exactly 10 digits.');
                    return; // Stop form submission
                }

                const encryptedPhone = encryptData(sanitizedNumber); // Encrypt the sanitized number
                $('#phone_number').val(encryptedPhone); // Replace the input value with the encrypted value

                this.submit(); // Submit the form after validation and encryption
            });


            $('#uploadForm').on('submit', async function(event) {
                event.preventDefault();

                const fileInput = document.getElementById("file");
                const existingFileSelect = document.getElementById("existing_file");
                const file = fileInput.files[0];
                let encryptedNumbers = [];

                if (file) {
                    encryptedNumbers = await readAndEncryptExcelFile(file);
                } else if (existingFileSelect.value) {
                    const file_path = existingFileSelect.value;
                    encryptedNumbers = await fetchAndEncryptExistingFile(file_path);
                } else {
                    return;
                }

                const encryptedInput = document.createElement("input");
                encryptedInput.type = "hidden";
                encryptedInput.id = "encrypted_file_data";
                encryptedInput.name = "encrypted_file_data";
                encryptedInput.value = JSON.stringify(encryptedNumbers);

                fileInput.remove();
                this.appendChild(encryptedInput);
                this.submit();
            });

            async function readAndEncryptExcelFile(file) {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        try {
                            const data = new Uint8Array(e.target.result);
                            const workbook = XLSX.read(data, {
                                type: 'array'
                            });
                            const sheet = workbook.Sheets[workbook.SheetNames[0]];
                            const rows = XLSX.utils.sheet_to_json(sheet, {
                                header: 1
                            });

                            const encryptedNumbers = [];
                            for (let i = 1; i < rows.length; i++) {
                                const phoneNumber = rows[i][0];
                                if (phoneNumber) {
                                    // Sanitize and format the number
                                    let sanitizedNumber = phoneNumber.toString().trim().replace(
                                        /[^\d]/g, ''); // Remove non-numeric characters

                                    // If the number starts with '91' and is longer than 10 digits, remove '91'
                                    if (sanitizedNumber.length > 10 && sanitizedNumber.startsWith(
                                            '91')) {
                                        sanitizedNumber = sanitizedNumber.substring(2);
                                    }

                                    // Ensure the final number is exactly 10 digits
                                    if (sanitizedNumber.length === 10) {
                                        const encryptedPhone = encryptData(
                                            sanitizedNumber); // Encrypt the formatted number
                                        encryptedNumbers.push(encryptedPhone);
                                    }
                                }
                            }
                            resolve(encryptedNumbers);
                        } catch (error) {
                            reject(error); // Handle and reject errors
                        }
                    };
                    reader.onerror = reject;
                    reader.readAsArrayBuffer(file);
                });
            }

            async function fetchAndEncryptExistingFile(file_path) {
                try {
                    const response = await fetch("{{ route('assistant.upload_files.getFile') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            file_path: file_path
                        }),
                    });

                    if (!response.ok) {
                        throw new Error('Failed to fetch the file.');
                    }

                    const blob = await response.blob();
                    const data = await blob.arrayBuffer();

                    const workbook = XLSX.read(new Uint8Array(data), {
                        type: 'array'
                    });
                    const sheet = workbook.Sheets[workbook.SheetNames[0]];
                    const rows = XLSX.utils.sheet_to_json(sheet, {
                        header: 1
                    });

                    const encryptedNumbers = [];
                    for (let i = 1; i < rows.length; i++) {
                        const phoneNumber = rows[i][0];
                        if (phoneNumber) {
                            // Sanitize and format the phone number
                            let sanitizedNumber = phoneNumber.toString().trim().replace(/[^0-9]/g,
                                ''); // Remove non-numeric characters

                            // Remove '91' if the number starts with it and is longer than 10 digits
                            if (sanitizedNumber.length > 10 && sanitizedNumber.startsWith('91')) {
                                sanitizedNumber = sanitizedNumber.substring(2);
                            }

                            // Ensure the number is exactly 10 digits
                            if (sanitizedNumber.length === 10) {
                                const encryptedPhone = encryptData(
                                    sanitizedNumber); // Encrypt the formatted number
                                encryptedNumbers.push(encryptedPhone);
                            }
                        }
                    }
                    return encryptedNumbers;
                } catch (error) {
                    console.error('Error processing file:', error);
                    return [];
                }
            }

        });
    </script>
@endsection
