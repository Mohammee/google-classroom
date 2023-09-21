import "./bootstrap.js";


(function ($) {

    function getMessages(page = 1) {
        $.ajax({
            method: "get",
            url: messages.store_url,
            headers: {
                'x-api-key': 'oMllsdzSU78zaRpoZejl5WoyeDdd0gBM',
            },
            success: function (response) {
                for (let i in response.data) {
                    let message = response.data[i];
                    addMessage(message, true);
                }
            }
        })
    }

    function addMessage(message, prepend = false) {
        let html = `
                                <div class="bg-info rounded p-2 mt-2">
                                    <div>
                                    <b>${message.sender.name}</b>
                                    - <spna class="text-muted">${message.sent_at}</span>
                                    </div>

                                    <div>${message.body}</div>
                                </div>
                                `;

        if (prepend) {
            return $('#messages').prepend(html);
        }

        $('#messages').append(html);
    }

    function send(message) {
        $.post(
            messages.store_url,
            {
                _token: csrf_token,
                body: message,
            },
            function () {
                // location.reload();
                addMessage({
                    sender: {
                        name: user.name,
                    },
                    body: message,
                    sent_at: (new Date()).toString(),
                })
            }
        )
    }

    $('#message-form').on('submit', function (e) {
        e.preventDefault();
        send($(this).find('textarea').val());
        $(this).find('textarea').val(null);
    })

    $(document).ready(function () {
        getMessages();
    })

    let ch = Echo.join('classroom-' + classroomId)
        .here((users) => {
            for (let i in users) {
                let user = users[i];
                $('ul#users').append(`
                <li id="user-${user.id}">${user.name}</li>
                `);
            }
        }).joining((user) => {
            $('ul#users').append(`
                <li id="user-${user.id}">${user.name}</li>
                `);
        })
        .leaving((user) => {
            $(`li#user-${user.id}`).remove();
        })
        .listen('.new-message', function (event) {
            addMessage(event)
        });

    //make checked client event  on pusher app settings
    // $('#message-form textarea').on('input', function (e) {
    //     ch.whisper('typing', {
    //         name: user.name
    //     });
    // })
    //
    // ch.whisper('stop-typing', {
    //     name: user.name
    // });
})(jQuery)


