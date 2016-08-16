new Vue({
    el: 'body',
    data: {
        step: 'credentials',
        error: false,
        user: {
            email: '',
            password: '',
            password_confirm: '',
            phone: '',
            phone_code: ''
        }
    },
    ready: function() {
        this.$els.email.focus();

        this.handleGivenStep();
    },
    methods: {
        handleGivenStep: function() {
            var virtual = document.createElement('a'),
                query,
                data = {};

            virtual.href = location.href;

            query = virtual.search.replace('?', '').split('=');
            query.forEach(function(key, index) {
                if (query[index + 1]) {
                    data[key] = query[index + 1]
                }
            })

            switch (data.step) {
                case 'phone':
                    this.error = 'This account already exists, but was never verified.';
                    this.step = 'phone';
                    break;
            }
        },
        requestThenNext: function(path, data, next) {
            this.error = false;

            this.$http.post(path, data)
                .then(function(response) {
                    if (response.data.message) {
                        this.error = response.data.message;
                    }

                    if (next) {
                        this.step = next;

                        this.$nextTick(function() {
                            if (this.$els[next]) {
                                this.$els[next].focus();
                            }
                        });
                    }
                })
                .catch(function(response) {
                    if (response.data.message) {
                        this.error = response.data.message;

                        return false;
                    }
                });
        },

        register: function() {
            if (this.user.password != this.user.password_confirm) {
                this.error = 'Passwords do not match';
                return false;
            }

            this.requestThenNext('/app/register', this.user, 'phone');
        },
        addPhone: function() {
            var data = {
                phone: this.user.phone
            };

            this.requestThenNext('/app/verify-phone', data, 'phone_code')
        },
        codeConfirm: function() {
            var data = {
                phone_code: this.user.phone_code
            };

            this.requestThenNext('/app/verify-phone-code', data, 'completed')
        }
    }
});
