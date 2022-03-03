<style>
.cursor-pointer{
    cursor: pointer
}
.badge{
    text-align: left;
    white-space: unset;
    line-height: 20px;
}
</style>
<template>
    <div class="container-sm pt-5" v-if="postData">
        <div class="mx-auto col-lg-6">
            <div class="card bg-white">
                <div class="card-body">
                    <p class="card-text">{{postData['content']}}</p>
                    <hr>
                    <p v-if="!commentsData.length">No comments yet</p>
                    <div v-for="(comment,index) in commentsData">
                        <p class='badge bg-light text-dark fs-6'>
                            {{ comment.content }}
                        </p>
                        <figcaption class="blockquote-footer cursor-pointer" @click="replyComment(comment)">
                            <cite title="Reply">Reply @{{comment.username}}</cite>
                            <vue-moments-ago prefix="posted" suffix="ago" :date="comment.created_at" lang="en"></vue-moments-ago>
                        </figcaption>
                        <div style="margin-left: 30px" v-for="(subComment,subIndex) in comment.comments">
                            ↳<p class='badge bg-light text-dark fs-6'>
                                {{ subComment.content }}
                            </p>
                            <figcaption class="blockquote-footer cursor-pointer" @click="replyComment(subComment)">
                                <cite title="Reply">Reply @{{subComment.username}}</cite>
                                <vue-moments-ago prefix="posted" suffix="ago" :date="subComment.created_at" lang="en"></vue-moments-ago>
                            </figcaption>
                            <div style="margin-left: 60px" v-for="(sub2Comment,sub2Index) in subComment.comments">
                                ↳<p class='badge bg-light text-dark fs-6'>
                                {{ sub2Comment.content }}
                            </p>
                                <figcaption class="blockquote-footer">
                                    <cite title="Cant Reply">@{{sub2Comment.username}}</cite>
                                    <vue-moments-ago prefix="posted" suffix="ago" :date="sub2Comment.created_at" lang="en"></vue-moments-ago>
                                </figcaption>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-white mt-3 mb-5">
                <div class="card-body">
                    <figcaption class="blockquote-footer mt-2" v-if="replyId >= 0">
                        <cite title="Reply">Replying: {{replyText}}</cite>
                    </figcaption>
                    <div class="mb-3">
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping">@</span>
                            <input type="text" class="form-control" ref="username" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping" v-model="replyData.username" :disabled="replySubmitted">
                        </div>
                    </div>
                    <div class="mb-3">
                            <textarea class="form-control" maxlength="255" ref="content" placeholder="Leave a comment here" id="floatingTextarea2" v-model="replyData.content" :disabled="replySubmitted" style="height: 100px"></textarea>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary" @click="saveComment()" :disabled="replyData.username.length<5 || replyData.content.length<5 || replySubmitted">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueMomentsAgo from "vue-moments-ago";

    export default {
        data(){
            return {
                url: '',
                postError: false,
                postLoading: true,
                postData: [],
                postId: 1,
                commentsError: false,
                commentsLoading: true,
                commentsData: [],
                replyId: -1,
                replyText: '',
                replySubmitted: false,
                replyData: {
                    username: '',
                    content: ''
                }
            }
        },
        components: {
            VueMomentsAgo
        },
        mounted() {
            this.getPost();
        },
        methods: {
            replyComment(comment){
                this.replyId = comment.id;
                this.replyText = comment.username + ' | ' + comment.content;

                setTimeout(() => {
                    if(this.replyData.username.length){
                        this.$refs.content.focus()
                    }else{
                        this.$refs.username.focus()
                    }
                    const container = this.$el.querySelector("body");
                    container.scrollTop = container.scrollHeight + 500;
                }, 200);


            },

            async getPost() {
                await axios.get('/api/posts/' + this.postId)
                    .then(({data}) => {
                        this.postLoading = false,
                        this.postData = data['data'];
                        this.getPostComments(this.postData['id']);
                    }).catch(() => {
                        this.postLoading = false,
                        this.postError = true
                    }).finally(() => {
                        this.postLoading = false
                    })
            },

            async getPostComments(post_id) {
                await axios.get('/api/posts/' + post_id + '/comments')
                    .then(({data}) => {
                        this.commentsData = data['data'][0];
                    }).catch(() => {
                        this.commentsError = true
                    }).finally(() => {
                        this.commentsLoading = false
                    })
            },

            async saveComment() {
                this.replySubmitted = true;
                if (this.replyData.username.length>=3 && this.replyData.content.length>=5) {
                    await axios.get('/sanctum/csrf-cookie')
                    if(this.replyId > 0){
                        this.url = 'api/comments/' + (this.replyId>0 ? this.replyId : this.postId) + '/comment'
                    }else{
                        this.url = 'api/posts/' + (this.replyId>0 ? this.replyId : this.postId) + '/comment';
                    }
                    await axios.post(this.url, this.replyData)
                        .then(({data}) => {
                            this.getPostComments(this.postId);
                            this.replySubmitted = false;
                            this.replyData.content = ''
                            this.replyId = -1;
                        })
                        .catch(({response: {data}}) => {
                            this.replySubmitted = false;
                        })
                        .finally(() => {
                            this.replySubmitted = false;
                        })
                }
            },
        }
    }
</script>
