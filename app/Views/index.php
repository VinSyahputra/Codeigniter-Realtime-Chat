<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Chat Layout</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@1.14.0/dist/full.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
</head>

<body class="bg-gray-100">
    <div class="h-screen flex">

        <div class="w-1/3 bg-white border-r overflow-y-auto">

            <div class="flex items-center justify-between bg-gray-800 text-white p-4 shadow-lg" style="height: 80px;">
                <div class="flex items-center">
                    <img src="https://via.placeholder.com/40" alt="User Avatar" class="rounded-full w-10 h-10">
                    <div class="ml-4">
                        <h1 class="text-lg font-semibold"><?= session()->get('username') ?></h1>
                    </div>
                </div>
                <button id="logout" class="btn btn-sm btn-outline-white btn-error">Logout</button>
            </div>

            <div class="p-4">
                <input type="text" placeholder="Search chats..." class="input input-bordered w-full" id="searchUser">
            </div>


            <div class="p-4 space-y-4">

            </div>
        </div>


        <div class="w-2/3 flex-1 flex flex-col relative">

            <div class="flex items-center bg-gray-800 text-white p-4 shadow-lg" style="height: 80px;">
                <img src="https://via.placeholder.com/40" alt="User Avatar" class="rounded-full w-10 h-10">
                <div class="ml-4">
                    <h1 class="text-lg font-semibold">Chat with User 1</h1>
                    <p class="text-sm text-gray-300">Last seen today at 12:45 PM</p>
                </div>
            </div>

            <!-- Chat Body -->
            <div class="flex-1 p-4 pb-0 overflow-y-auto">
                <div class="space-y-4 position-relative pb-24" id="message-body">

                </div>
            </div>
            <form action="send" method="post" style="position: absolute; bottom:0; width: 100%;">
                <div class="flex items-center bg-white p-4 border-t">
                    <input type="hidden" name="receiver_id" id="input_receiver_id">
                    <input type="text" name="message" placeholder="Type a message" class="input input-bordered w-full mr-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send</button>

                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            getAllMessage();

        });

        var pusher = new Pusher('9eeb4c738de702b10872', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('new-app');

        channel.bind('update-chat', function(res) {
            let data = res.data;
            console.log('Event received:', data.message); // This should log the event data if received
            console.log('Event received:', data.sender_id); // This should log the event data if received
            let chatBody = document.querySelector('#message-body');
            let chat = document.createElement('div');
            chat.classList.add('flex');
            if (<?php echo session()->get('id') ?> == data.sender_id) {
                chat.classList.add('justify-end');
            }
            let chatBubble = document.createElement('div');
            chatBubble.classList.add('flex', 'flex-col');
            chatBubble.style.maxWidth = '80%';
            chatBubble.style.wordWrap = 'break-word';
            chatBubble.style.wordBreak = 'break-all';
            chatBubble.style.whiteSpace = 'pre-wrap';
            chatBubble.style.overflowWrap = 'break-word';
            chatBubble.style.minWidth = '100px';
            if (<?php echo session()->get('id') ?> == data.sender_id) {
                chatBubble.classList.add('bg-blue-500', 'text-white', 'p-3', 'rounded-lg', 'max-w-xs');
            } else {
                chatBubble.classList.add('bg-gray-200', 'text-gray-800', 'p-3', 'rounded-lg', 'max-w-xs');
            }
            chatBubble.innerText = data.message;
            let chatDate = document.createElement('small');
            chatDate.classList.add('text-xs', 'text-white-400', 'mt-1');
            chatDate.innerText = moment(data.created_at).format('HH:mm');
            chatBubble.appendChild(chatDate);
            chat.appendChild(chatBubble);


            chatBody.appendChild(chat);
            scrollToBottom();

        });

        let btnLogout = document.getElementById('logout');
        btnLogout.addEventListener('click', () => {
            fetch('/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                }
            }).catch(err => console.error(err)).finally(() => {
                window.location.href = '/login';
            });
        });



        let getData = (props) => {

            $.ajax({
                url: '/getMessages',
                type: 'POST',
                dataType: 'json',
                data: {
                    receiver_id: props?.receiver_id,
                    sender_id: <?= session()->get('id') ?>,
                    message: props?.message,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    let data = response;
                    console.log(data);
                    let chatBody = document.querySelector('#message-body');
                    chatBody.innerHTML = '';
                    if (data.length == 0) {
                        chatBody.innerHTML = '<p class="text-center text-gray-400">No message found</p>';
                    } else {
                        let lastDate = null;
                        data.forEach(message => {
                            let chat = document.createElement('div');
                            chat.classList.add('flex');
                            if (<?php echo session()->get('id') ?> == message.sender_id) {
                                chat.classList.add('justify-end');
                            }

                            let currentDate = moment(message.created_at).format('DD MMM YYYY');

                            // Check if the current date is different from the last date
                            if (currentDate !== lastDate) {
                                let dateContainer = document.createElement('div');
                                dateContainer.classList.add('w-full', 'text-center');
                                let dateElement = document.createElement('small');
                                dateElement.classList.add('text-xs', 'text-gray-400', 'mt-1', 'text-center', 'w-full');
                                dateElement.innerText = currentDate;
                                dateContainer.appendChild(dateElement);
                                chatBody.appendChild(dateContainer);

                                // Update the last shown date
                                lastDate = currentDate;
                            }

                            let chatBubble = document.createElement('div');
                            chatBubble.classList.add('flex', 'flex-col');
                            chatBubble.style.maxWidth = '80%';
                            chatBubble.style.wordWrap = 'break-word';
                            chatBubble.style.wordBreak = 'break-all';
                            chatBubble.style.whiteSpace = 'pre-wrap';
                            chatBubble.style.overflowWrap = 'break-word';
                            chatBubble.style.minWidth = '100px';
                            if (<?php echo session()->get('id') ?> == message.sender_id) {
                                chatBubble.classList.add('bg-blue-500', 'text-white', 'p-3', 'rounded-lg', 'max-w-xs');
                            } else {
                                chatBubble.classList.add('bg-gray-200', 'text-gray-800', 'p-3', 'rounded-lg', 'max-w-xs');
                            }
                            chatBubble.innerText = message.message;
                            let chatDate = document.createElement('small');
                            chatDate.classList.add('text-xs', 'text-white-400', 'mt-1');
                            chatDate.innerText = moment(message.created_at).format('HH:mm');
                            chatBubble.appendChild(chatDate);
                            chat.appendChild(chatBubble);


                            chatBody.appendChild(chat);
                            scrollToBottom();
                        });
                    }

                    console.log(props?.receiver_id);
                    // let formControl = `
                    //         <form action="send" method="post" style="position: absolute; bottom:0; width:100% !important">
                    //             <div class="flex items-center bg-white p-4 border-t">
                    //             <input type="hidden" name="receiver_id" value="${props?.receiver_id}">
                    //                 <input type="text" name="message" placeholder="Type a message" class="input input-bordered w-full mr-2">
                    //                 <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send</button>

                    //             </div>
                    //         </form>
                    //     `;

                    // chatBody.innerHTML += formControl;
                    $('#input_receiver_id').val(props?.receiver_id);
                    let form = document.querySelector('form');
                    form.addEventListener('submit', (e) => {
                        e.preventDefault();
                        let formData = new FormData(form);
                        $.ajax({
                            url: '/send',
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                form.reset();
                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', error);
                            }
                        });
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });

        }

        $('#searchUser').on('input', function() {
            let value = $(this).val().toLowerCase();
            getAllMessage({
                value: value
            });

        });

        let getAllMessage = (props) => {
            fetch('/searchUser', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    },
                    body: JSON.stringify({
                        value: props?.value
                    })
                }).then(response => response.json())
                .then(data => {
                    let chatList = document.querySelector('.space-y-4');
                    chatList.innerHTML = '';
                    if (data.length == 0) {
                        chatList.innerHTML = '<p class="text-center text-gray-400">No user found</p>';
                    } else {

                        data.forEach(user => {
                            let chatItem = document.createElement('div');
                            chatItem.classList.add('flex', 'items-center', 'p-2', 'bg-gray-100', 'rounded-lg', 'cursor-pointer', 'hover:bg-gray-200');
                            chatItem.innerHTML = `
                            <img src="https://via.placeholder.com/40" alt="User Avatar" class="rounded-full w-10 h-10">
                            <div class="ml-4 flex-1">
                                <h2 class="text-sm font-semibold">${user.username}</h2>
                                <p class="text-xs text-gray-500 truncate">Last message from ${user.username}...</p>
                            </div>
                            <span class="text-xs text-gray-400">12:45 PM</span>
                        `;
                            chatItem.addEventListener('click', () => {
                                getData({
                                    receiver_id: user.id
                                });
                            })


                            chatList.appendChild(chatItem);
                            scrollToBottom();
                        });
                    }

                });
        }


        const scrollToBottom = () => {
            const scrollToBottom = () => {
                const chatBody = document.getElementById('chatBody');
                if (chatBody) {
                    chatBody.scrollTop = chatBody.scrollHeight;
                }
            };

        }
    </script>
</body>

</html>