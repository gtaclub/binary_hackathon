<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default chatPanel">
                        <div class="panel-heading">Binary Customer Service</div>
                        <div class="panel-body chatContent">

                            <div class="row left">
                                <div class="col-md-2">
                                    <img src="https://ih1.redbubble.net/image.409697982.2737/flat,800x800,075,t.u3.jpg"
                                         class="avatar align-center">
                                </div>
                                <div class="col-md-7">
                                    <div class="bubble">
                                        Hey guest231! May I help you ?
                                    </div>
                                    <div class="carett">
                                        <i class="fa fa-caret-left fa-2x" aria-hidden="true"></i>
                                    </div>
                                    <span class="under-bubble">Elon Musk</span>
                                </div>
                            </div>

                            <span v-for="item in items">

                                <div class="row left" v-if="item.type == 'left'">
                                    <div class="col-md-2">
                                        <img src="https://ih1.redbubble.net/image.409697982.2737/flat,800x800,075,t.u3.jpg"
                                             class="avatar align-center">
                                    </div>
                                    <div class="col-md-7">
                                        <div class="bubble">
                                            {{item.message}}
                                        </div>
                                        <div class="carett">
                                            <i class="fa fa-caret-left fa-2x" aria-hidden="true"></i>
                                        </div>
                                        <span class="under-bubble">Elon Musk</span>
                                    </div>
                                </div>

                                <div class="row right" v-if="item.type == 'right'">
                                    <div class="col-md-7 col-md-offset-3">
                                        <div class="bubble">
                                            {{item.message}}
                                        </div>
                                         <div class="carett">
                                        <i class="fa fa-caret-left fa-2x" aria-hidden="true"></i>
                                    </div>
                                        <span class="under-bubble">Guest231</span>
                                    </div>
                                    <div class="col-md-2">
                                        <img src="http://media.istockphoto.com/vectors/cat-avatar-vector-id519049345?k=6&m=519049345&s=612x612&w=0&h=RB-fF6RArVIY-zCIlA24W0RVhtaqc0nWAeKoXn4_900="
                                             class="avatar align-center">
                                    </div>
                                </div>
                            </span>

                            <div class="row left" v-if="loading">
                                <div class="col-md-2">
                                    <img src="https://ih1.redbubble.net/image.409697982.2737/flat,800x800,075,t.u3.jpg"
                                         class="avatar align-center">
                                </div>
                                <div class="col-md-7">
                                    <div class="bubble">
                                        <div class="lds-css ng-scope">
                                            <div style="width:20px;height:20px" class="lds-ellipsis">
                                                <div>
                                                    <div></div>
                                                </div>
                                                <div>
                                                    <div></div>
                                                </div>
                                                <div>
                                                    <div></div>
                                                </div>
                                                <div>
                                                    <div></div>
                                                </div>
                                                <div>
                                                    <div></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="carett">
                                            <i class="fa fa-caret-left fa-2x" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="chatText">
                            <input type="text" class="form-control chat-bar" @keydown="sendMsg" v-model="msgText">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            var self = this
            socket.on('homepage', function (data) {
                console.log("message received")
                self.items.push({'message': data.replied, 'type': 'left'})
                self.loading = false
            })
        },
        data() {
            return {
                items: [],
                msgText: '',
                loading: false,

            }
        },
        updated() {
            var el = document.getElementsByClassName('chatContent')[0]
            el.scrollTop = el.scrollHeight
        },
        methods: {
            sendMsg(e) {
                var self = this
                if (e.keyCode === 13 && !e.shiftKey) {
                    var msg = this.msgText
                    this.msgText = this.msgText.substring(0, 0)
                    if (msg.match(/\S/g)) {
                        this.$http.post('//localhost/reply', {'message': msg})
                        self.items.push({'message': msg, 'type': 'right'})
                        self.loading = true
                    }
                }
            }
        },
    }
</script>
