<script>
import Replies from "../components/Replies.vue";
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