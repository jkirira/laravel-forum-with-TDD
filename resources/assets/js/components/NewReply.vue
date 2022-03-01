<template>

    <div v-if="signedIn">
        <div class="form-group">
            <textarea
                  v-model="body"
                  name="body"
                  id="body"
                  class="form-control"
                  placeholder="Have something to say?"
                  rows = 5
                  required></textarea>
        </div>

        <button type="submit"
                class="btn btn-default"
                @click="addReply">Post</button>
    </div>
    <div v-else>
        <p  class="text-center">
            Please <a href="/login">sign in</a> to participate in this discussion
        </p>
    </div>

</template>

<script>
    import 'at.js';
    import 'jquery.caret';
    export default {
        data(){
            return{
                body: ''
            }
        },
        computed: {
            signedIn(){
                return window.app.signedIn;
            }
        },

        mounted() {
            $('body').atwho({
                at: '0',
                delay: 750,
                callbacks: {
                    remoteFilter: function (query, callback){
                        $.getJSON("api/users", {name: query}, function(usernames){
                            callback(usernames)
                        })
                    }
                }
            })
        },
        methods: {
            addReply(){
                axios.post(location.pathname + '/replies', {body: this.body})
                      .catch(error => {
                          console.log('Error');
                          flash(error.response.data)
                      })
                    .then(response => {

                        this.body = ''

                        flash('Your reply has been posted.')

                        this.$emit('created', response.data)
                    })
            }
        }
    }
</script>

<style></style>