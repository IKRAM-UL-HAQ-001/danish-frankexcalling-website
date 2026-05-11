@extends("layouts.main")
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-11 mb-xl-0 mx-auto my-5 border w-full bg-white rounded d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom mb-5">
                <h2 class="mb-0">Uploaded Excel Files</h2>
                <button type="button" class="btn btn-secondary ms-2" style="background: #344767;" data-bs-toggle="modal" data-bs-target="#fileModal">Upload Excel Files</button>
            </div>

            <div class="table-responsive p-0">
                <table id="DataTable" class="table align-items-center mb-0 table-striped table-hover">
                    <thead>
                        <tr>
                            <th>File Name</th>
                            <th>Uploaded At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($Files as $file)
                        <tr>
                            <td>{{ $file->file_name }}</td>
                            <td>{{ $file->uploaded_at }}</td>
                            <td>
                                <div class="d-flex">
                                    <button class="btn btn-info btn-sm mx-3" data-bs-toggle="modal" data-bs-target="#viewFileModal" onclick="viewFileData({{ $file->id }})">View</button>
                                    <form method="POST" action="{{ route('admin.upload_files.delete') }}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $file->id }}">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="fileModalLabel">Upload Excel Files</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" method="post" action="{{ route('admin.upload_files.formPost') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="files" class="form-label">Select Files</label>
                        <input type="file" class="form-control" id="files" name="files[]" multiple required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- View File Modal -->
<div class="modal fade" id="viewFileModal" tabindex="-1" aria-labelledby="viewFileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewFileModalLabel">File Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="overflow-y: auto; max-height: 70vh;">
                <table id="fileDataTable" class="table align-items-center mb-0 table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Data</th> <!-- Adjust header as necessary -->
                        </tr>
                    </thead>
                    <tbody id="fileDataBody">
                        <!-- Data will be populated dynamically via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function viewFileData(fileId) {
        console.log("Fetching data for fileId:", fileId);
        fetch("{{ route('admin.upload_files.view') }}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ id: fileId })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(data => {
            console.log("File data received:", data);
            const fileDataBody = document.getElementById('fileDataBody');
            fileDataBody.innerHTML = ''; // Clear previous data

            if (data.fileData && Array.isArray(data.fileData)) {
                // Skip the first row (headers or unwanted row)
                data.fileData.slice(1).forEach(row => {
                    const tr = document.createElement('tr');
                    const td = document.createElement('td');
                    td.textContent = row[0]; // Adjust based on the file's structure
                    tr.appendChild(td);
                    fileDataBody.appendChild(tr);
                });
            }
        })
        .catch(error => {
            console.error('Error fetching file data:', error);
            alert("An error occurred while fetching file data. Please try again.");
        });
    }
</script>
@endsection
