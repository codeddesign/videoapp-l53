new Vue({
    el: 'body',
    data: {
        error: false,
        user: {
            email: '',
            password: ''
        }
    },

    ready: function () {
        this.$els.email.focus()
    },

    methods: {
        login: function () {
            this.error = false;

            this.$http.post('/login', this.user).then(function (response) {
                this.user.password = '';

                if (response.data.redirect) {
                    location.href = response.data.redirect;

                    return false;
                }

                console.error('Failed to authenticate. Contact dev if problem persists.');
            }).catch(function (response) {
                this.user.password = '';

                this.error = (response.data) ? response.data.message : response;
            });
        }
    }
});
