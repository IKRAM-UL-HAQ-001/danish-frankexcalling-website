<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PhoneNumbers Decryption</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
</head>

<body>
    <button id="decryptButton">Decrypt and Update</button>
    
    <button id="correctFormat">Correct Format</button>
    <a href="{{ route('updatetest') }}">Update Test</a>

    <script>
        const secretKey = CryptoJS.enc.Utf8.parse('{{ config('app.aes_encrypt_key') }}'); // 16-byte key for AES
        const iv = CryptoJS.enc.Hex.parse('00000000000000000000000000000000'); // 16-byte fixed IV

        function encryptData(data) {
            return CryptoJS.AES.encrypt(data, secretKey, {
                iv: iv
            }).toString();
        }

        function decryptData(encryptedData) {
            if (!encryptedData || encryptedData.length === 0) {
                return ''; // Return empty if no valid data is found
            }

            try {
                const decrypted = CryptoJS.AES.decrypt(encryptedData, secretKey, {
                    iv: iv
                });
                return decrypted.toString(CryptoJS.enc.Utf8);
            } catch (e) {
                console.error('Error during decryption:', e);
                return ''; // Return empty if decryption fails
            }
        }

        document.getElementById('decryptButton').addEventListener('click', async () => {
            try {
                const response = await fetch('/api/get-phone-numbers');
                const data = await response.json();

                if (data && data.phoneNumbers) {
                    const decryptedData = data.phoneNumbers.map((item) => {

                            return {
                                id: item.id,
                                phoneNumber: encryptData(item.phone),
                                // amount: encryptData(item.amount),
                                // name: encryptData(item.name),
                                // feedback: encryptData(item.feedback),
                            };
                    });
                    console.log('Decrypted Data:', decryptedData);

                    // Send decrypted data back to the server
                    const updateResponse = await fetch('/api/update-phone-numbers', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            phoneNumbers: decryptedData
                        }),
                    });

                    const updateResult = await updateResponse.json();
                    console.log('Update Result:', updateResult);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });

        document.getElementById('correctFormat').addEventListener('click', async () => {
            try {
                alert('Correct format');
                const response = await fetch('/api/get-phone-numbers');
                const data = await response.json();

                if (data && data.phoneNumbers) {
                    console.log(data.phoneNumbers);
                    const correctedData = data.phoneNumbers
                        .map((item) => {
                            if (!item.phone_number) return null; // Check for null or undefined

                            const phoneNumber = item.phone_number;
                            const sanitizedNumber = phoneNumber.toString().trim().replace(/[^\d]/g, '');

                            const finalNumber = sanitizedNumber.length > 10 && sanitizedNumber.startsWith('91')
                                ? sanitizedNumber.substring(2)
                                : sanitizedNumber;

                            return finalNumber.length >= 10 ? { id: item.id, phoneNumber: finalNumber } : null; // Exclude invalid numbers
                        })
                        .filter((item) => item !== null); // Remove null entries

                    console.log('Corrected Data:', correctedData);

                    const updateResponse = await fetch('/api/update-phone-numbers', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            phoneNumbers: correctedData
                        }),
                    });

                    const updateResult = await updateResponse.json();
                    console.log('Update Result:', updateResult);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    </script>
</body>

</html>


