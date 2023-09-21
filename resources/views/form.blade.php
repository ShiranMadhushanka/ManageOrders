<!DOCTYPE html>
<html>
<head>
    <title>IndexedDB Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            text-align: center;
        }

        h1 {
            padding: 20px 0;
        }

        form {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            display: inline-block;
            text-align: left;
            width: 500px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 96%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 14px;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 80%;
            max-width: 600px;
            background-color: #fff;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
    <body>
        <h1>IndexedDB Form</h1>
        <form id="messageForm">
            <label for="name">Name:</label>
            <input type="text" id="name" required><br><br>
            <span id="nameError" class="error"></span><br>

            <label for="email">Email:</label>
            <input type="email" id="email" required><br><br>
            <span id="emailError" class="error"></span><br>

            <label for="message">Message:</label>
            <textarea name="message" id="message" required></textarea><br><br>
            <span id="messageError" class="error"></span><br>

            <button type="submit">Submit</button>
        </form>

        <h2>IndexedDB Data</h2>
        <table id="dataTable" border="1">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script>
            // Index DB implementation
            document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('messageForm');
            const dataTable = document.getElementById('dataTable').getElementsByTagName('tbody')[0];

            const request = indexedDB.open('IndexDB', 1);

            request.onerror = function (event) {
                console.error('IndexedDB error:', event.target.error);
            };

            request.onsuccess = function (event) {
                const db = event.target.result;

                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const name = document.getElementById('name').value;
                    const email = document.getElementById('email').value;
                    const message = document.getElementById('message').value;

                    const transaction = db.transaction(['orders'], 'readwrite');
                    const objectStore = transaction.objectStore('orders');

                    const order = {
                        name: name,
                        email: email,
                        message: message,
                    };

                    const request = objectStore.add(order);

                    request.onsuccess = function (event) {
                        console.log('Data added to IndexedDB');
                        form.reset();
                        displayDataFromIndexedDB();
                    };

                    request.onerror = function (event) {
                        console.error('IndexedDB error:', event.target.error);
                    };
                });

                displayDataFromIndexedDB();

                function displayDataFromIndexedDB() {
                    dataTable.innerHTML = '';

                    const objectStore = db.transaction('orders').objectStore('orders');
                    objectStore.openCursor().onsuccess = function (event) {
                        const cursor = event.target.result;
                        if (cursor) {
                            const row = dataTable.insertRow();
                            const cell1 = row.insertCell(0);
                            const cell2 = row.insertCell(1);
                            const cell3 = row.insertCell(2);

                            cell1.textContent = cursor.value.name;
                            cell2.textContent = cursor.value.email;
                            cell3.textContent = cursor.value.message;

                            cursor.continue();
                        }
                    };
                }
            };

            request.onupgradeneeded = function (event) {
                const db = event.target.result;
                const objectStore = db.createObjectStore('orders', { keyPath: 'id', autoIncrement: true });

                objectStore.createIndex('email', 'email', { unique: false });
                };
            });

        </script>
    </body>
</html>