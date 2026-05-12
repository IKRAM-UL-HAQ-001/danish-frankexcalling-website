<footer class="footer pt-3">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between ">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="copyright text-center text-sm text-white text-lg-start">
                    ©
                    <script>
                        document.write(new Date().getFullYear())
                    </script>,
                    <a href="#" class="font-weight-bold" style="color:white" target="_blank">HIF Solution</a>
                    for a better web.
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

    <!-- DataTable Initialization -->
    <script>
        $(document).ready(function() {
            if (!$.fn.DataTable.isDataTable('#DataTable')) {
                $('#DataTable').DataTable({
                    pagingType: "full_numbers",
                    order: [
                        [0, 'desc']
                    ],
                    language: {
                        paginate: {
                            first: '«',
                            last: '»',
                            next: '›',
                            previous: '‹'
                        }
                    },
                    lengthMenu: [5, 10, 25, 50, 5000],
                    pageLength: 10
                });
            }
        });
    </script>

    <script>
        const secretKey = CryptoJS.enc.Utf8.parse('{{ config('app.aes_encrypt_key') }}'); // 16-byte key for AES
        const iv = CryptoJS.enc.Hex.parse('00000000000000000000000000000000'); // 16-byte fixed IV

        function encryptData(data) {
            return data;
        }

        function decryptData(encryptedData) {
            return encryptedData;
        }


        $(document).ready(function() {
            // Decryption loop removed
        });
    </script>
</footer>
