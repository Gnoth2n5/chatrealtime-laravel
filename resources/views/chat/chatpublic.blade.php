@extends('layouts.app')

@section('style')
    <style>
        .item img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 5px;
            object-fit: cover;
            object-position: center;

        }

        .item {
            display: flex;
            padding: 10px;
            align-items: center;
            background: rgb(233, 233, 233);
            border-bottom: 1px solid rgb(221, 216, 216);
            position: relative;
        }

        .item:hover {
            opacity: 0.8;
        }

        .status {
            position: absolute;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: green;
            top: 5px;
        }

        .block-chat {
            width: 100%;
            height: 400px;
            overflow-y: scroll;
            border: 1px solid rgb(190, 180, 180);
            background: rgb(244, 249, 255);
            list-style: none;
        }

        .block-chat li{
            border: 1px solid gray;
            padding: 10px;
            margin-right: 10px;
            margin-top: 10px;
            border-radius: 10px; 
        }

        .message{
            color: blue;
            text-align: right;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="row">

                    @foreach ($users as $user)
                        <div class="col-md-12">
                            <a href="" class="item" id="link_{{ $user->id }}">
                                <img src="{{ $user->image }}" alt="">
                                <p>{{ $user->name }}</p>
                            </a>
                        </div>
                    @endforeach


                </div>
            </div>
            <div class="col-md-9">
                <ul class="block-chat rounded-2">

                </ul>
                <form>
                    <div class="d-flex">
                        <input type="text" class="form-control me-2" id="inputChat">
                        <button type="button" class="btn btn-outline-success" id="btnSnd">Gửi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        Echo.join('chat')
            .here(users => {
                users.forEach(item => {
                    let el = document.querySelector(`#link_${item.id}`)
                    // console.log(el);
                    let elStatus = document.createElement('div');
                    elStatus.classList.add('status');
                    if (el) {
                        el.appendChild(elStatus);
                    }
                });
            })
            .joining(user => {
                let el = document.querySelector(`#link_${user.id}`)
                // console.log(el);
                let elStatus = document.createElement('div');
                elStatus.classList.add('status');
                if (el) {
                    el.appendChild(elStatus);
                }
            })
            .leaving(user => {
                let el = document.querySelector(`#link_${user.id}`)
                // console.log(el);
                let elStatus = document.querySelector('.status');
                if (elStatus) {
                    el.removeChild(elStatus);
                }
            })
            .listen('UserOnlined', event => {
                // console.log(event.user.name);
                let blkChat = document.querySelector('.block-chat')
                let elChat = document.createElement('li');
                elChat.textContent = `${ event.user.name }: ${event.message}`
                // console.log(elChat.textContent); 
                if(event.user.id == {{ Auth::user()->id }}) {
                    elChat.classList.add('message')
                }
                blkChat.appendChild(elChat);
            })


        let inputChat = document.querySelector('#inputChat')
        let btnSnd = document.querySelector('#btnSnd')

        btnSnd.addEventListener('click', () => {
            axios.post('{{ route('sendMessage') }}', {
                'message': inputChat.value
            })
                .then(data => {
                    // console.log(data.data.success);
                    inputChat.value = ''
                })
        })
    </script>
@endsection
