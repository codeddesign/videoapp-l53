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

        <div class="sitevalidation-worksaddtl"><span>NEW WEBSITE APPROVAL MAY TAKE UP TO 48 HOURS.</span>
          <br>YOU WILL RECEIVE AN EMAIL ONCE YOUR WEBSITE HAS BEEN APPROVED.
        </div>
      </div>

      <div class="accountpass-accountidwrap" style="margin-bottom:30px;">
        <div class="accountpass-accountidtitle" style="margin-bottom:12px;border-top:1px solid #DDDDDD;padding-top:40px;">WEBSITES ADDED</div>

        <div v-for="site in sites">
          <div class="accountpass-accountid">
            <div class="sitevalidation-sitelink">{{ site.link }}</div>
            <div class="sitevalidation-timestamp">{{ formatDate(site.created_at) }}</div>
            <!-- site approval alert -->

            <!-- end approval alert -->
            <button v-on:click="remove(site)" class="sitevalidation-removesite">REMOVE</button>

            <div v-show="site.approved" class="sitevalidation-siteapproved">APPROVED</div>
            <div v-show="! site.approved" class="sitevalidation-sitepending">PENDING</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import http from '../../services/http'
  import moment from 'moment'

  export default {
    data() {
      return {
        site: {
          link: ''
        },
        sites: []
      }
    },

    mounted() {
      this.$nextTick(function() {
        http.get('/websites')
            .then((response) => {
              this.sites = response.data.data
            })
      })
    },

    methods: {
      add() {
        http.post('/websites', { domain: this.site.link })
            .then((response) => {
              this.sites.push(response.data.site)
            })
            .catch((error) => {
              console.error(error)
              return false
            })
      },

      remove(site) {
        http.delete('/websites/' + site.id)
            .then((response) => {
              var index = this.sites.indexOf(site)
              this.sites.splice(index, 1)
            })
      },

      formatDate(timestamp) {
        return moment(timestamp * 1000).format('MMMM D, YYYY')
      }
    }
  }
</script>

<style lang="scss">
  .sitevalidation-removesite {
    padding: 0;
  }
</style>
