<template>
    <div v-if="subscribers !== null">
        {{ subscribers }} {{ subscribers | pluralize('subscriber') }} &nbsp;
        <button class="btn btn-xs btn-default" v-if="canSubscribe" @click.prevent="handle">
            <i class="glyphicon glyphicon-refresh spinning" v-if="subscribing"></i>
            {{ userSubscribed ? 'Unsubscribe' : 'Subscribe' }}
        </button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                subscribers: null,
                userSubscribed: false,
                canSubscribe: false,
                subscribing: false,
            }
        },
        props: {
            channelSlug: null,
        },
        methods: {
            getSubscriptionStatus() {
                this.$http.get('/subscription/' + this.channelSlug).then(Vue.getJson).then((response) => {
                    this.subscribers = response.data.count;
                    this.userSubscribed = response.data.user_subscribed;
                    this.canSubscribe = response.data.can_subscribe;
                })
            },
            handle() {
                this.subscribing = true;

                if (this.userSubscribed) {
                    this.unsubscribe();
                } else {
                    this.subscribe();
                }
            },
            subscribe() {
                this.userSubscribed = true;
                this.subscribers++;

                this.$http.post('/subscription/' + this.channelSlug).then(() => { this.subscribing = false });
            },
            unsubscribe() {
                this.userSubscribed = false;
                this.subscribers--;

                this.$http.delete('/subscription/' + this.channelSlug).then(() => { this.subscribing = false });
            }
        },
        mounted() {
            this.getSubscriptionStatus();
        }
    }
</script>