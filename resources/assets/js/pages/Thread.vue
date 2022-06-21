<script>
import Replies from "../components/Replies.vue";
import SubscribeButton from "../components/Subscribebutton.vue";
export default {
    props: ['dataRepliesCount', 'dataLocked'],
    components: {
        Replies
    },
     data(){
        return {
            repliesCount: this.dataRepliesCount,
            locked: this.dataLocked
        }
     }
 }
</script>