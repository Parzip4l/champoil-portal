
@extends('layout.master')
<style>
    .chat-container {
        max-width: 900px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        height: 800px;
        border: 1px solid #eee6e6;
        border-radius:10px;
    }

    .chat-header {
        background-color: #4E73DF;
        padding: 15px;
        color: white;
        text-align: center;
        font-size: 1.25rem;
    }

    .chat-body {
        flex-grow: 1;
        overflow-y: auto;
        padding: 15px;
        background-color: #F7F7F9;
        border-top: 1px solid #E2E2E2;
        border-bottom: 1px solid #E2E2E2;
    }

    .chat-footer {
        padding: 10px;
        border-top: 1px solid #E2E2E2;
        display: flex;
        align-items: center;
    }

    .chat-input {
        flex-grow: 1;
        padding: 10px;
        border-radius: 25px;
        border: 1px solid #E2E2E2;
    }

    .send-button {
        background-color: #4E73DF;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 50%;
        cursor: pointer;
        margin-left: 10px;
    }

    .message {
        margin-bottom: 10px;
        display: inherit;
        align-items: flex-start;
    }

    .message .message-text {
        max-width: 70%;
        padding: 8px 12px;
        margin-bottom:3px;
        border-radius: 20px;
        background-color: #E1E1E1;
    }

    .message .message-text.sent {
        background-color: #4E73DF;
        color: white;
        align-self: flex-end;
        margin-bottom:3px;
        display: inherit;
        margin-left: auto; /* Aligns the message to the right */
    }

    .message .message-time {
        margin-left: 10px;
        font-size: 0.75rem;
        color: #aaa;
    }

</style>
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif  

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@php 
    $user = Auth::user();
    $dataLogin = json_decode(Auth::user()->permission); 
    $employee = \App\Employee::where('nik', Auth::user()->name)->first(); 
@endphp
<div class="row">
    <div class="col-md-8">
        <div  class="row">
            <div class="col-md-4">
                <div class="card custom-card2">
                    <div class="card-header">
                        <div class="head-card d-flex justify-content-between">
                            <div class="header-title align-self-center">
                                <h6 class="card-title align-self-center mb-0">To Do</h6>
                                
                            </div>
                        
                        </div>
                    </div>
                    <div class="card-body">
                    
                        <div id="todo">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card custom-card2">
                    <div class="card-header">
                        <div class="head-card d-flex justify-content-between">
                            <div class="header-title align-self-center">
                                <h6 class="card-title align-self-center mb-0">On Progress</h6>
                                
                            </div>
                        
                        </div>
                    </div>
                    <div class="card-body">
                    
                        <div id="onprog">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card custom-card2">
                    <div class="card-header">
                        <div class="head-card d-flex justify-content-between">
                            <div class="header-title align-self-center">
                                <h6 class="card-title align-self-center mb-0">Done</h6>
                                
                            </div>
                        
                        </div>
                    </div>
                    <div class="card-body">
                    
                        <div id="done">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div  class="row mt-3">
            <div class="col-md-12">
                <div class="card custom-card2">
                    <div class="card-header">
                        <div class="head-card d-flex justify-content-between">
                            <div class="header-title align-self-center">
                                <h6 class="card-title align-self-center mb-0">Voice Of Guardians</h6>
                                
                            </div>
                        
                        </div>
                    </div>
                    <div class="card-body">
                    
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Project</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Rating</th>
                                    
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
  <div class="col-md-4">
        <div class="chat-container">
            <!-- Chat Header -->
            <div class="chat-header">
                Chat with Support
            </div>

            <!-- Chat Body -->
            <div class="chat-body" id="chatBody">
                <!-- Messages will appear here -->
            </div>

            <!-- Chat Footer -->
            <div class="chat-footer">
                
                
            </div>
        </div>
  </div>
  
</div>

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".chat-footer").empty();
            $("#todo").empty();
            $("#onprog").empty();
            $("#done").empty();
            $('#dataTableExample').DataTable({
                ajax: {
                    url: '/api/v1/voice',  // Replace with your actual API endpoint
                    type: 'GET',
                    dataSrc: 'records'  // Point to the "records" key in the response
                },
                columns: [
                    {
                        data: 'id', 
                        name: 'id',
                        render: function(data, type, row) {
                            return `<a href="javascript:void(0)" onclick="viewDetails(${data})">${data}</a>`;
                        }
                    },
                    { 
                        data: 'nama', 
                        name: 'nama',
                        render: function(data, type, row) {
                            return `<a href="javascript:void(0)" onclick="viewDetails(${row.id})">${data}</a>`;
                        }
                    },
                    { data: 'project', name: 'project' },
                    { data: 'created_at', name: 'tanggal' },
                    { data: 'status', name: 'status' },
                    { data: 'rating', name: 'rating' }
                ]
            });

            // Example JavaScript function for the click event
            viewDetails = function(id) {
                $("#chatBody").empty(); // Clear previous chat content
                axios.get('/api/v1/voice-detail/' + id)
                    .then(function(response) {
                        const data = response.data;

                        if (data.error === false && data.records.length > 0) {
                            // Loop through each record and append it to the chat body
                            data.records.forEach(record => {
                                // Create a new message element for each record
                                let messageElement = document.createElement("div");
                                messageElement.classList.add("message");

                                // Format the main message text
                                let messageText = `
                                    Project: ${record.project} <br>
                                    Name: ${record.nama} <br>
                                    Pertanyaan: ${record.pertanyaan || "N/A"} <br>
                                    Date: ${record.created_at}
                                `;

                                // Create and add message text element
                                let messageTextElement = document.createElement("div");
                                messageTextElement.classList.add("message-text");
                                messageTextElement.innerHTML = messageText;
                                messageElement.appendChild(messageTextElement);

                                // Loop through the percakapan array and add each conversation entry
                                record.percakapan.forEach(chat => {
                                    const chatElement = document.createElement("div");
                                    if(chat.voice_user===0){
                                        chatElement.classList.add("message-text");
                                    }else{
                                        chatElement.classList.add("message-text","sent");
                                    }
                                    
                                    chatElement.innerHTML = `${chat.jawaban || "N/A"} <br>
                                        ${new Date(chat.created_at).toLocaleString()}
                                    `;
                                    messageElement.appendChild(chatElement);
                                });

                                // Append the message to the chat body
                                document.getElementById("chatBody").appendChild(messageElement);

                                // Update chat footer with the input and send button
                                document.querySelector(".chat-footer").innerHTML = `
                                    <input type="text" id="messageInput" class="chat-input" placeholder="Type your message...">
                                    <button class="send-button" onclick="sendMessage('${record.nomor_wa}', '${record.id}')">&#8594;</button>
                                `;
                            });

                            // Scroll to the latest message
                            document.getElementById("chatBody").scrollTop = document.getElementById("chatBody").scrollHeight;
                        } else {
                            console.log('No records found or error in response');
                        }
                    })
                    .catch(function(error) {
                        console.error('Error:', error);
                        Swal.fire({
                            title: "Error!",
                            text: 'There was an error fetching the data.',
                            icon: "error"
                        });
                    });
            };



            axios.get('/api/v1/voice')
                .then(function (response) {
                    $("#todo").text(response.data.todo);
                    $("#onprog").text(response.data.prog);
                    $("#done").text(response.data.done);
                })
                .catch(function (error) {
                    // Handle error
                    console.error('Error:', error);
                    Swal.fire({
                        title: "Error!",
                        text: 'There was an error fetching the data.',
                        icon: "error"
                    });
                });


        });

    </script>
    <script>
        function sendMessage(nomor_wa,record_id) {
            
            const url = 'https://waapi.app/api/v1/instances/17816/client/action/send-message';
            let messageText = document.getElementById("messageInput").value;
            if (messageText.trim() === '') return;

            // Add message to chat body
            const messageElement = document.createElement("div");
            messageElement.classList.add("message");

            const messageTextElement = document.createElement("div");
            messageTextElement.classList.add("message-text", "sent");
            messageTextElement.textContent = messageText;

            const messageTime = document.createElement("span");
            messageTime.classList.add("message-time");
            messageTime.textContent = new Date().toLocaleTimeString();

            messageElement.appendChild(messageTextElement);
            messageElement.appendChild(messageTime);

            document.getElementById("chatBody").appendChild(messageElement);
            document.getElementById("messageInput").value = '';

            const data = {
                chatId:  `${nomor_wa}@c.us`,
                message: messageText
            };

            const headers = {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': 'Bearer QB3r7rcz8AhMyvMiYMeP4VAhf0R996eQBmnFLrs627a36a08'
            };
            //submit relations
            const formData  ={
                voice_id:record_id,
                voice_user:1,
                jawaban:messageText,
            };
            axios.post('/api/v1/voice-detail-submit',formData)
            .then(response => {
                
            })
            .catch(error => {
                console.error('Error sending message:', error.response ? error.response.data : error.message);
                Swal.fire({
                    title: "Error!",
                    text: 'Failed to send the message.',
                    icon: "error"
                });
            });

            axios.post(url, data, { headers })
                .then(response => {

                    


                })
                .catch(error => {
                    console.error('Error sending message:', error.response ? error.response.data : error.message);
                    Swal.fire({
                        title: "Error!",
                        text: 'Failed to send the message.',
                        icon: "error"
                    });
                });

            document.getElementById("chatBody").scrollTop = document.getElementById("chatBody").scrollHeight;
        }
    </script>
@endpush