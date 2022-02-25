<script>
import Replies from "../components/Replies.vue";
import SubscribeButton from "../components/Subscribebutton.vue";
export default {
    props: ['initialrepliescount'],
    components: {
        Replies
    },
     data(){
        return {
            repliesCount: this.initialrepliescount
        }
     }
 }
</script>