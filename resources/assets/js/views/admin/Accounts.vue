<template>
  <div>
    <div class="accounts-dropsearch">
      <form>
        <input v-model="search" class="campaignview-search" name="all_search" id="all_search" placeholder="search for..">
      </form>
      <!-- DROP FORM -->
      <form action="#" method="post">
          <div class="campaignview-searchicon"></div>

          <div class="campaignview-dropbutton" v-on:click="toggleAdvancedSearch">advanced search</div>
          <div class="campaignview-droppedarea" v-show="advancedSearch">
              <div class="campview-dropwhere">
                  <div class="campview-droptitle">WHERE</div>
                  <select v-model="filters[0]['option']" name="ad_campaign_select">
                      <option value="account_id">Account ID</option>
                      <option value="company">Company</option>
                      <option value="first_name">First Name</option>
                      <option value="last_name">Last Name</option>
                      <option value="created_at_humans">Since Date</option>
                      <option value="website">Website</option>
                  </select>
                  <div class="campview-selectarrow"></div>
              </div>
              <div class="campview-dropsearchfor">
                  <div class="campview-droptitle">SEARCH FOR</div>
                  <div class="campview-searchinput">
                      <input v-model="filters[0]['value']" type="text" name="ad_campaign_value">
                      <div class="campview-searchinputicon"></div>
                  </div>
              </div>
              <div class="campview-dropandwhere">
                  <div class="campview-droptitle">WHERE</div>
                  <select v-model="filters[1]['option']" name="video_rev_select">
                      <option value="campaigns">Campaigns</option>
                      <option value="revenue">Revenue</option>
                  </select>
                  <div class="campview-selectarrow"></div>
              </div>
              <div class="campview-dropmin">
                  <div class="campview-droptitle">MIN</div>
                  <input v-model="filters[1]['min']" type="text" name="min">
              </div>
              <div class="campview-droptomax">
                  <div class="campview-droptitle">MAX</div>
                  <input v-model="filters[1]['max']" type="text" name="max">
              </div>
          </div>
      </form>
      <!-- END DROP FORM -->
    </div>

    <!-- CAMPAIGN SELECTION AREA -->
    <ul class="dashboard-dailystatstitles dashboard-accountstitles" style="border-top:0;">
        <li>ACCOUNT ID</li>
        <li>COMPANY</li>
        <li>FNAME</li>
        <li>LNAME</li>
        <li>SINCE</li>
        <li>CAMPAIGNS</li>
        <li>30-DAY REVENUE</li>
        <li>ACTIVE</li>
    </ul>
    <ul class="dashboard-dailystatslist dashboard-accountslist">
      <li v-for="account in filteredAccounts">
        <div @click="showAccount(account)">
          <div class="dashboard-statslist1">{{ account.id }}</div>
          <div class="dashboard-statslist2">{{ account.company }}</div>
          <div class="dashboard-statslist2">{{ account.first_name }}</div>
          <div class="dashboard-statslist2">{{ account.last_name }}</div>
          <div class="dashboard-statslist2">{{ account.created_at_humans }}</div>
          <div class="dashboard-statslist2">{{ account.campaigns.data.length }}</div>
          <div class="dashboard-statslist2">{{ accounting.formatMoney(account.revenue) }}</div>
        </div>
        <div class="dashboard-statslist2">
          <div class="dashboard-switch">
            <input v-bind:id="account.id" v-on:change="activateAccount(account.id, $event)" class="cmn-toggle cmn-toggle-round-flat cmn-togglechange" type="checkbox" v-bind:checked="account.active">
            <label v-bind:for="account.id" class="cmn-labelchange"></label>
          </div>
        </div>
      </li>
    </ul>
    <div class="understatlist-wrapper">
      <div class="create-account-button" @click="newAccount()">
        Create New Account
      </div>
      <div class="dashpagination-wrapper">
        <div @click="pagination.previousPage()" class="dashpag-left"></div>
        <div class="dashpag-numbers">{{ pagination.currentPage() }} of {{ pagination.totalPages() }}</div>
        <div @click="pagination.nextPage()" class="dashpag-right"></div>
      </div>
      <div class="dashpagerows-wrapper">
        <div class="dashpagerows-title">Display Rows:</div>
        <select v-model="pagination['perPage']">
          <option v-for="option in pagination['perPageOptions']" v-bind:value="option">{{ option }}</option>
        </select>
        <div class="dashpagerows-selectarrow"></div>
      </div>
    </div>
  </div>
</template>

<script>
  import Fuse from 'fuse.js'
  import _ from 'lodash'
  import accounting from 'accounting'
  import Pagination from '../../services/pagination'

  export default {
    name: 'Accounts',

    data() {
      return {
        search: '',

        filters: [
          {
            option: '',
            value: ''
          },
          {
            option: '',
            min: '',
            max: ''
          }
        ],

        accounting: accounting,

        pagination: new Pagination(),

        advancedSearch: false
      }
    },

    computed: {
      accounts() {
        return this.$store.state.admin.accounts
      },

      valueFilterSet() {
        return this.filters[0]['option'] !== '' && this.filters[0]['value'] !== ''
      },

      rangeFilterSet() {
        return this.filters[1]['option'] !== '' &&
        (this.filters[1]['min'] !== '' || this.filters[1]['max'] !== '')
      },

      filteredAccounts() {
        let filteredAccounts = this.accounts

        if (this.valueFilterSet) {
          if (this.filters[0]['option'] === 'website') {
            filteredAccounts = filteredAccounts.filter((account) => {
              return account.websites.data.filter((website) => {
                return _.includes(
                  website.domain,
                  this.filters[0]['value'].toLowerCase()
                )
              }).length > 0
            })
          } else {
            filteredAccounts = filteredAccounts.filter((account) => {
              return _.includes(
                account[this.filters[0]['option']].toLowerCase(),
                this.filters[0]['value'].toLowerCase()
              )
            })
          }
        }

        if (this.rangeFilterSet) {
          filteredAccounts = filteredAccounts.filter((account) => {
            let min = this.filters[1]['min'] !== '' ? this.filters[1]['min'] : 0
            let max = this.filters[1]['max'] !== '' ? this.filters[1]['max'] : false
            let option = this.filters[1]['option']

            // Set the right field for comparison
            let value = null
            if (option === 'campaigns') {
              value = account[option].data.length
            }
            if (option === 'revenue') {
              value = account.revenue
            }

            if (max) {
              return value >= min && value <= max
            } else {
              return value >= min
            }
          })
        }

        if (this.search !== '') {
          var options = {
            threshold: 0.3,
            keys: [
              'first_name',
              'last_name',
              'company',
              'created_at_humans'
            ]
          }

          var fuse = new Fuse(filteredAccounts, options)

          filteredAccounts = fuse.search(this.search)
        }

        this.pagination.data = filteredAccounts
        return this.pagination.getData()
      }
    },

    methods: {
      toggleAdvancedSearch() {
        this.advancedSearch = !this.advancedSearch
      },

      activateAccount(id, event) {
        this.$store.dispatch('admin/activateUser', {
          id: id,
          status: event.target.checked
        })
      },

      showAccount(account) {
        this.$router.push({ name: 'admin.accounts.info', params: { accountId: account.id }})
      },

      newAccount(account) {
        this.$router.push({ name: 'admin.accounts.new' })
      }
    },

    mounted() {
      this.$store.dispatch('admin/loadAccounts')
    }
  }
</script>

<style lang="scss">
  .create-account-button {
    float: left;
    margin-top: 10px;
    cursor: pointer;
    cursor: hand;
    color: #373F52;
    font-size: 14px;
    margin-left: 12px;
    font-weight: 600;
    margin-right: 10px;
  }
</style>
