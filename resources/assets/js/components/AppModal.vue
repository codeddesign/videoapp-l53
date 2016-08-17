<template>
    <div class="app_modal-overlay" v-show="visible">
        <div class="app_modal">
            <span class="app_modal-close" @click="close">&times;</span>
            <div class="app_modal-body">
                <div class="app_modal-title" v-show="title">{{ title }}</div>
                <div class="app_modal-content" v-show="body">
                    <div v-if="!html"> {{ body }} </div>
                    <div v-else>{{{ body }}}</div>
                </div>

                <div class="app_modal-footer" v-show="confirm">
                    <button class="proceed" @click="proceed" :disabled="loading">Proceed</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default
    {
        data: () => {
            return {
                visible: false,
                title: false,
                body: false,
                confirm: false,
                callback: false,
                loading: false,
                html: false
            }
        },
        events: {
            'modal-open': (setup, callback) => {
                this.title = setup.title || false;
                this.body = setup.body || false;
                this.confirm = setup.confirm || false;
                this.html = setup.html || false;
                this.callback = callback || false;
                this.loading = false;
                this.open();
            },
            'modal-close': () => this.close()
        },
        methods: {
            open(callback) {this.visible = true;},
            close() {this.visible = false;},
            proceed() {
                if (typeof this.callback == 'function') {
                    this.loading = true;

                    this.callback();

                    return false;
                }

                this.close();
            }
        }
    }
</script>
