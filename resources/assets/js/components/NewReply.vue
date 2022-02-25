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
    export default {
        props: ['endpoint'],
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
        method: {
            addReply(){
                axios.post(this.endpoint, {body: this.body})
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