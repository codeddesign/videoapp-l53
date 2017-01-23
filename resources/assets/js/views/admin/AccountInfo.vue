<template>
  <div class="page-index">
    <div class="userinfo-abovegraph">
      <div class="userinfo-returntosearch" @click="goBack()">RETURN TO SEARCH</div>
      <div class="userinfo-useractivate">
        <div class="userinfo-activatetitle">ACCOUNT ACTIVATION</div>
        <div class="userinfo-activateoff"></div>
        <div class="userinfo-activateon"></div>
      </div>
    </div>

    <div class="userinfo-graphwrap">
      <div class="userinfo-graphleft">
        <ul class="userinfo-userdatalist">
          <li>
            <div class="userinfo-listtitle">NAME</div>
            <div class="userinfo-listdesc">{{ account.first_name }} {{ account.last_name }}</div>
          </li>
          <li>
            <div class="userinfo-listtitle">COMPANY</div>
            <div class="userinfo-listdesc">{{ account.company }}</div>
          </li>
          <li>
            <div class="userinfo-listtitle">EMAIL</div>
            <div class="userinfo-listdesc">{{ account.email }}</div>
          </li>
          <li>
            <div class="userinfo-listtitle">TELEPHONE</div>
            <div class="userinfo-listdesc">{{ account.phone_number }}</div>
          </li>
          <li>
            <div class="userinfo-listtitle">ADDRESS</div>
            <div class="userinfo-listdesc account_address">{{ account.address }}</div>
          </li>
          <li>
            <div class="userinfo-listtitle">BANK DETAILS</div>
            <div class="userinfo-listdesc">
              <div class="userinfo-bankinfobutton" @click="showBankInfo = !showBankInfo">Click to Display</div>
            </div>
            <!-- BANK DETAILS MODAL -->
            <div class="userinfo-bankinfomodal" v-show="showBankInfo">
              <div class="userbankclose" @click="showBankInfo = false"></div>
              <div class="userinfo-bankinfoinner">
                <div class="userinfo-bankinfotitle">NAME ON ACCOUNT</div>
                <div class="userinfo-bankinfosub">{{ account.bank_details.account_name || 'N/A' }}</div>
              </div>
              <div class="userinfo-bankinfoinner">
                <div class="userinfo-bankinfotitle">BANK NAME</div>
                <div class="userinfo-bankinfosub">{{ account.bank_details.bank_name || 'N/A' }}</div>
              </div>
              <div class="userinfo-bankinfoinner">
                <div class="userinfo-bankinfotitle">BANK ADDRESS</div>
                <div class="userinfo-bankinfosub" style="margin-bottom:10px;">
                  {{ account.bank_details.bank_address || 'N/A' }}
                </div>
              </div>
              <div class="userinfo-bankinfoinner">
                <div class="userinfo-bankinfotitle">ACCOUNT #</div>
                <div class="userinfo-bankinfosub">{{ account.bank_details.account_number || 'N/A' }}</div>
              </div>
              <div class="userinfo-bankinfoinner">
                <div class="userinfo-bankinfotitle">ROUTING #</div>
                <div class="userinfo-bankinfosub">{{ account.bank_details.routing_number || 'N/A' }}</div>
              </div>
            </div>
            <!-- END BANK DETAILS MODAL -->
          </li>
        </ul>
      </div>
      <div class="userinfo-graphright">
        <account-chart :chart-data="chartData"></account-chart>

        <div class="userinfo-addtllinkswrap">
          <ul class="userinfo-addtllinks">
            <li>MANAGE TAGS</li>
            <li>VIEW ANALYTICS</li>
            <li>RUN REPORT</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- BEGIN TABS -->
    <div class="userinfo-selectwrap">
    <div class="tabmanage-headerbar"></div>
      <div class="tagmanage-tabbed">
        <!-- START WEBSITE TAB -->
        <div>
          <input name="tagmanage-tabbed" id="tagmanage-tabbed12" type="radio" checked>
          <section>
            <h1>
              <label for="tagmanage-tabbed12">WEBSITES</label>
            </h1>
            <div>
              <div class="userinfo-websitesheader">
                <div class="userinfo-websitestitle">ACCOUNT WEBSITES</div>
              </div>
              <ul class="userinfo-itemlistheader">
                <li>SUPPLIER</li>
                <li>REQUESTS</li>
                <li>IMPRESSIONS</li>
                <li>FILL-RATE</li>
                <li>ERROR-RATE</li>
                <li>TAG DISPLAY %</li>
                <li>STATE</li>
              </ul>
              <!-- START ACCOUNT WEBSITES LIST -->
              <ul class="admindashboard-dailystatslist userinfo-statslist">
                <li v-for="website in websites">
                  <div>
                    <div class="dashboard-statslist1">{{ website.domain }}</div>
                    <div class="dashboard-statslist2">{{ website.stats.requests }}</div>
                    <div class="dashboard-statslist2">{{ website.stats.impressions }}</div>
                    <div class="dashboard-statslist2">
                      {{ calculateFillRate(website.stats.impressions, website.stats.requests) }}
                    </div>
                    <div class="dashboard-statslist2">
                      {{ calculateErrorRate(website.stats.impressions, website.stats.errors) }}
                    </div>
                    <div class="dashboard-statslist2">17%</div>
                  </div>
                  <div class="dashboard-statslist3">
                    <div class="dashboard-switch">
                      <input v-bind:id="website.id" type="checkbox" v-on:change="activateWebsite(website.id, $event)" class="cmn-toggle cmn-toggle-round-flat cmn-togglechange" v-bind:checked="website.approved">
                      <label v-bind:for="website.id" class="cmn-labelchange"></label>
                    </div>
                  </div>
                </li>
              </ul>
              <!-- END ACCOUNT WEBSITES LIST -->
            </div>
          </section>
        </div>
        <div>
          <!-- START CAMPAIGNS TAB -->
          <input name="tagmanage-tabbed" id="tagmanage-tabbed13" type="radio">
          <section>
            <h1>
              <label for="tagmanage-tabbed13">CAMPAIGNS</label>
            </h1>
            <div>
              <p>coming soon..</p>
            </div>
          </section>
        </div>
        <div>
          <!-- START TAGS TAB -->
          <input name="tagmanage-tabbed" id="tagmanage-tabbed14" type="radio">
          <section>
            <h1>
              <label for="tagmanage-tabbed14">TAGS</label>
            </h1>
            <div>
              <p>coming soon..</p>
            </div>
          </section>
        </div>
        <div>
          <!-- START NOTES TAB -->
          <input name="tagmanage-tabbed" id="tagmanage-tabbed15" type="radio">
          <section>
            <h1>
              <label for="tagmanage-tabbed15">NOTES</label>
            </h1>
            <div>
              <div class="userinfo-noteswrapper">
                <div class="userinfo-notesinfotitle">ACCOUNT INFO</div>
                <div class="userinfo-notescounttitle">{{ account.notes.data.length }} NOTES</div>
                <div class="userinfo-notesinnerwrapper">
                  <div class="userinfo-chatbubblewrap" v-for="note in account.notes.data">
                    <div class="userinfo-chatbubbleusername">{{ note.creator_name }}</div>
                    <div class="userinfo-chatbubbledate">{{ note.created_at_humans }}</div>
                    <div class="userinfo-chatbubble">{{ note.content }}</div>
                  </div>
                </div>

                <div class="userinfo-commentmakecomment">
                  <textarea v-model="note" class="userinfo-commenttextarea" placeholder="add your comment.."></textarea>
                  <p class="userinfo-commentsubmitnote" @click="addNote()">ADD NEW NOTE</p>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div><!-- END TABS .tagmanage-tabbed -->
    </div><!-- END .userinfo-selectwrap -->
  </div><!-- end page-index -->
</template>

<script>
  import _ from 'lodash'
  import stats from '../../services/stats'
  import User from '../../models/user'
  import Admin from '../../models/admin'
  import AccountChart from './AccountChart.vue'

  export default {
    name: 'AccountInfo',

    data() {
      return {
        showBankInfo: false,
        note: '',
        websites: [],
        stats: [],
        chartData: []
      }
    },

    methods: {
      activateWebsite(id, event) {
        Admin.activateWebsite(id, event.target.checked)
      },

      goBack() {
        this.$router.push({ name: 'admin.accounts' })
      },

      addNote() {
        this.$store.dispatch('addNote', { account: this.account, note: this.note })
      },
      ...stats
    },

    computed: {
      account() {
        let account = _.find(this.$store.state.admin.accounts, { 'id': parseInt(this.$route.params.accountId) })

        if (account === undefined) {
          this.$store.dispatch('loadAccounts')
          return {
            bank_details: {},
            notes: {
              data: []
            }
          }
        }

        User.loadChart(account.id)
          .then(chartData => {
            this.chartData = chartData
          })

        this.$store.dispatch('loadWebsitesStats', account)
        return account
      },

      websites() {
        return this.$store.state.admin.websitesStats
      }
    },

    components: {
      AccountChart
    }
  }
</script>

<style lang="scss">
  .account_address {
    white-space: pre-wrap;
  }

  .userinfo-returntosearch, .userinfo-commentsubmitnote {
    cursor: pointer;
    cursor: hand;
  }
</style>
