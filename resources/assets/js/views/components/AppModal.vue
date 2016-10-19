<template>
    <div class="app_modal-overlay" v-show="visible">
        <div class="app_modal">
            <span class="app_modal-close" @click="close">&times;</span>
            <div class="app_modal-body">
                <div class="app_modal-title" v-show="title">{{ title }}</div>
                <div class="app_modal-content" v-show="body">
                    <div v-if="!html">
                        <div v-html="body"></div>
                    </div>
                    <div v-else>
                        <div v-html="body"></div>
                    </div>
                </div>

                <div class="app_modal-footer" v-show="confirm">
                    <button class="proceed" @click="proceed" :disabled="loading">Proceed</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'App-Modal',
        props: {
            visible: {
              type: Boolean,
              required: true
            },
            title: {
              type: String,
              required: true
            },
            body: {
              required: true
            },
            confirm: {
              required: false
            },
            callback: {
              required: false
            },
            html: {
              required: false
            }
        },
        data() {
            return {
                loading: false
            }
        },
        methods: {
            proceed() {
                if (typeof this.callback == 'function') {
                    this.loading = true;

                    this.callback();

                    return false;
                }

                //this.close();
            },
            close() {
                this.$emit('close')
            }
        }
    }
</script>
