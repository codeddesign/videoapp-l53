<template>
    <div>
        <div class="accountpasswrap page-websiteconfig" v-cloak>
            <div class="accountpass-leftsep" style="width:100%;">
                <div class="display-septext">SITE VALIDATION</div>
            </div>

            <form action="#" method="post" @submit.prevent="add">
                <div>
                    <label>ADD A NEW SITE</label>
                    <input name="link" placeholder="http://example.com" required v-model="site.link">
                </div>

                <button type="submit">REQUEST APPROVAL</button>
            </form>

            <div class="sitevalidation-howitworkswrap">
                <div class="sitevalidation-howitworks"><span></span> HOW APPROVAL WORKS</div>

                <div class="sitevalidation-worksaddtl"><span>NEW WEBSITE APPROVAL MAY TAKE UP TO 24 HOURS.</span>
                    <br>YOU WILL RECIEVE AN EMAIL ONCE YOUR WEBSITE HAS BEEN APPROVED.</div>
            </div>

            <div class="accountpass-accountidwrap" style="margin-bottom:30px;">
                <div class="accountpass-accountidtitle" style="margin-bottom:12px;border-top:1px solid #DDDDDD;padding-top:40px;">WEBSITES ADDED</div>

                <div v-for="site in sites">
                    <div class="accountpass-accountid">
                        <div class="sitevalidation-sitelink">{{ site.link }}</div>
                        <div class="sitevalidation-timestamp">MARCH 15, 2016</div>
                        <!-- site approval alert -->

                        <!-- end approval alert -->
                        <button v-on:click="remove(site)" class="sitevalidation-removesite">REMOVE</button>

                        <div class="sitevalidation-siteapproved">APPROVED</div>
                        <div class="sitevalidation-sitepending">PENDING</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                site: {
                    link: ''
                },
                sites: []
            };
        },

        mounted() {
            this.$nextTick(function () {
                this.$http.get('/api/wordpress').then((response) => {
                    this.$set('sites', response.data.data);
                });
            })
        },

        methods: {
            add() {
                this.$http.post('/api/wordpress', {domain: this.site.link}).then((response) => {
                    if (response.data.error) {
                        alert(response.data.error);
                        return false;
                    }
                    this.sites.push(response.data.site);
                });
            },

            remove(site) {
                this.$http.delete('/api/wordpress/' + site.id).then(() => {
                    var index = this.sites.indexOf(site);
                    this.sites.splice(index, 1)
                });
            }
        }
    }
</script>
