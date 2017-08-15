<template>
  <div class="page-index">
    <div class="tagmanage-tagcreationwrapper" style="margin-top:50px;min-height:820px;" v-show="showBackfillForm">
      <div class="tagcreate-topbuttonswrap">
        <div class="tagcreate-topcancel" @click="backfillFormVisible(false)">CANCEL EDIT</div>
      </div>
      <div class="tagcreate-formwrapper">
        <div class="tagcreate-fullheadertitle">BACKFILL CREATION / EDITING</div>

        <div class="tagcreate-formbg">
          <div class="tagcreate-fullinnertitle">BACKFILL EMBED</div>
          <input class="tagcreate-longinput" v-model="currentBackfill['embed']" placeholder="paste backfill embed script...">
        </div>

        <div class="tagcreate-formbg">
          <div class="tagcreate-halfinnerwrap">
            <div class="tagcreate-fullinnertitle">ADVERTISER NAME</div>
            <input class="tagcreate-longinput" v-model="currentBackfill['advertiser']" placeholder="A3M">
          </div>
        </div>

        <div class="tagcreate-formbg">
          <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
            <div class="tagcreate-fullinnertitle">AD TYPE</div>
            <div class="tagcreate-selectwrap">
              <select class="tagcreate-dropdown" v-model="currentBackfill['ad_type_id']">
                <option value="3">Pre-roll</option>
                <option value="1">On-Scroll</option>
                <option value="2">Infinity</option>
              </select>
              <div class="tagcreate-selectarrow"></div>
            </div>
          </div>
          <div class="tagcreate-quarterinnerwrap">
            <div class="tagcreate-fullinnertitle">BACKFILL WIDTH</div>
            <div class="tagcreate-selectwrap">
              <select class="tagcreate-dropdown" v-model="currentBackfill['width']">
                <option value="responsive">Responsive</option>
                <option value="640">640px</option>
                <option value="300">300px</option>
              </select>
              <div class="tagcreate-selectarrow"></div>
            </div>
          </div>
          <div class="tagcreate-quarterinnerwrap">
            <div class="tagcreate-fullinnertitle">PLATFORM TYPE</div>
            <div class="tagcreate-selectwrap">
              <select class="tagcreate-dropdown" v-model="currentBackfill['platform_type']">
                <option value="desktop">Desktop</option>
                <option value="mobile">Mobile</option>
              </select>
              <div class="tagcreate-selectarrow"></div>
            </div>
          </div>
        </div>

        <div class="tagcreate-formbg">
          <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
            <div class="tagcreate-fullinnertitle">WEBSITE SELECTION</div>
            <div class="tagcreate-selectwrap">
              <select class="tagcreate-dropdown" v-model="currentBackfill['website_id']">
                <option v-for="website in websites" v-bind:value="website.id">
                  {{ website.domain }}
                </option>
              </select>
              <div class="tagcreate-selectarrow"></div>
            </div>
          </div>
          <div class="tagcreate-quarterinnerwrap">
            <div class="tagcreate-fullinnertitle">ECPM $</div>
            <input class="tagcreate-longinput" v-model="currentBackfill['ecpm']" placeholder="0.00">
          </div>
        </div>

      </div>

      <div class="tagcreate-savetagwrap">
        <div class="tagcreate-savetagbutton" @click="saveBackfillForm">SAVE BACKFILL CONFIGURATION</div>
        <div class="tagcreate-canceltagbutton" @click="backfillFormVisible(false)">CANCEL</div>
      </div>

    </div><!-- END .tagmanage-tagcreationwrapper -->
    <div class="userinfo-abovegraph">
      <div class="userinfo-returntosearch" @click="goBack()">RETURN TO SEARCH</div>
      <div class="userinfo-useractivate">
        <div class="userinfo-activatetitle">ACCOUNT ACTIVATION</div>
        <div @click="activateAccount(account.id, false)" v-bind:class="{ userInactive: !account.active }" class="userinfo-activateoff"></div>
        <div @click="activateAccount(account.id, true)" v-bind:class="{ userActive: account.active }" class="userinfo-activateon"></div>
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
            <a @click="impersonate()">
              <li>VIEW ACCOUNT DASHBOARD</li>
            </a>
            <router-link :to="{ name: 'admin.analytics'}">
              <li>VIEW ANALYTICS</li>
            </router-link>
            <router-link :to="{ name: 'admin.reports'}">
              <li>RUN REPORT</li>
            </router-link>
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
                <div class="addnewwebsitebutton" v-on:click="addNewWebsite()">ADD NEW WEBSITE</div>
              </div>
              <ul class="userinfo-itemlistheader userinfo-websitelistheader">
                <li>URL</li>
                <li>APPROVAL</li>
                <li>O&O</li>
                <li>STATE</li>
              </ul>
              <!-- START ACCOUNT WEBSITES LIST -->
              <ul class="admindashboard-dailystatslist userinfo-statslist userinfo-websitelist">
                <li v-for="website in websites">
                  <div>
                    <div class="dashboard-statslist1">{{ website.domain }}</div>
                    <div class="dashboard-statslist2" v-bind:class="websiteApprovedClass(website)">
                      {{ websiteApprovedStatus(website) }}
                    </div>
                    <div class="dashboard-statslist2">
                      <div class="dashboard-switch">
                        <input v-bind:id="'owned' + website.id" type="checkbox" v-on:change="ownedWebsite(website.id, $event)" class="cmn-toggle cmn-toggle-round-flat cmn-togglechange" v-bind:checked="website.owned">
                        <label v-bind:for="'owned' + website.id" class="cmn-labelchange"></label>
                      </div>
                    </div>
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
          <input name="tagmanage-tabbed" id="tagmanage-tabbed13" type="radio">
          <section>
            <h1>
              <label for="tagmanage-tabbed13">WEBSITE STATS</label>
            </h1>
            <div>
              <div class="userinfo-websitesheader">
                <div class="userinfo-websitestitle">WEBSITE STATS</div>
                <div class="edittagsselect-wrapper comparetagsselect-wrapper" style="margin-top: 4px;">
                  <select v-model="websiteDateFilter">
                    <option value="today">
                      Today
                    </option>
                    <option value="yesterday">
                      Yesterday
                    </option>
                    <option value="twoDaysAgo">
                      2 Days Ago
                    </option>
                    <option value="threeDaysAgo">
                      3 Days Ago
                    </option>
                    <option value="oneWeekAgo">
                      7 Days Ago
                    </option>
                    <option value="thisMonth">
                      This Month
                    </option>
                    <option value="lastMonth">
                      Last Month
                    </option>
                  </select>
                  <div class="edittagsselect-selectarrow" style="margin-left: 80px;"></div>
                </div>
              </div>
              <ul class="userinfo-itemlistheader">
                <li style="width:250px;">URL</li>
                <li style="border-right:1px solid #E3E1E0">PAGEVIEWS</li>
                <li style="border-right:1px solid #E3E1E0">REQUESTS</li>
                <li style="border-right:1px solid #E3E1E0">IMPRESSIONS</li>
                <li>FILL-RATE</li>
                <li>ERROR-RATE</li>
                <li>WEBSITE DISPLAY %</li>
                <li>STATE</li>
              </ul>
              <!-- START ACCOUNT WEBSITES LIST -->
              <ul class="admindashboard-dailystatslist userinfo-statslist">
                <li v-for="website in websites" style="height: auto !important;">
                  <div>
                    <div class="dashboard-statslist1" style="width:250px;">{{ website.domain }}</div>
                    <div class="dashboard-statslist2">{{ website.stats.mobilePageviews + website.stats.desktopPageviews }}</div>
                    <div class="dashboard-statslist2">{{ website.stats.tagRequests }}</div>
                    <div class="dashboard-statslist2">{{ website.stats.impressions }}</div>
                    <div class="dashboard-statslist2">
                      {{ calculateFillRate(website.stats.fills, website.stats.tagRequests) }}
                    </div>
                    <div class="dashboard-statslist2">
                      {{ calculateErrorRate(website.stats.tagRequests, website.stats.errors) }}
                    </div>
                    <div class="dashboard-statslist2">
                      {{ calculateTagDisplayPercent(website.stats.impressions, totalWebsiteImpressions(websites)) }}
                    </div>
                  </div>
                  <div class="dashboard-statslist3">
                    <div class="dashboard-switch">
                      <input v-bind:id="'stats' + website.id" type="checkbox" v-on:change="activateWebsite(website.id, $event)" class="cmn-toggle cmn-toggle-round-flat cmn-togglechange" v-bind:checked="website.approved">
                      <label v-bind:for="'stats' + website.id" class="cmn-labelchange"></label>
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
          <input name="tagmanage-tabbed" id="tagmanage-tabbed14" type="radio">
          <section>
            <h1>
              <label for="tagmanage-tabbed14">CAMPAIGNS</label>
            </h1>
            <div>
              <div class="userinfo-websitesheader">
                <div class="userinfo-websitestitle">CAMPAIGN STATS</div>
                <div class="edittagsselect-wrapper comparetagsselect-wrapper" style="margin-top: 4px;">
                  <select v-model="campaignsDateFilter">
                    <option value="yesterday">
                      Yesterday
                    </option>
                    <option value="twoDaysAgo">
                      2 Days Ago
                    </option>
                    <option value="threeDaysAgo">
                      3 Days Ago
                    </option>
                    <option value="oneWeekAgo">
                      7 Days Ago
                    </option>
                    <option value="thisMonth">
                      This Month
                    </option>
                    <option value="lastMonth">
                      Last Month
                    </option>
                  </select>
                  <div class="edittagsselect-selectarrow" style="margin-left: 80px;"></div>
                </div>
              </div>
              <ul class="userinfo-itemlistheader userinfo-campaigntitle">
                <li class="width250">CAMPAIGN NAME</li>
                <li class="width120">PLAYER TYPE</li>
                <li class="width120">REQUESTS</li>
                <li class="width120">IMPRESSIONS</li>
                <li class="width120">FILL-RATE</li>
                <li class="width120">USE-RATE</li>
                <li>ERROR-RATE</li>
                <li class="width161">STATE</li>
              </ul>
              <ul class="admindashboard-dailystatslist userinfo-campaignlist">
                <li v-for="campaign in campaigns">
                  <div>
                    <div class="dashboard-statslist1 width250">{{ campaign.name }}</div>
                    <div class="dashboard-statslist2 width120">{{ campaign.ad_type_name }}</div>
                    <div class="dashboard-statslist2">{{ campaign.stats.tagRequests }}</div>
                    <div class="dashboard-statslist2">{{ campaign.stats.impressions }}</div>
                    <div class="dashboard-statslist2 width120">
                      {{ calculateFillRate(campaign.stats.fills, campaign.stats.tagRequests) }}
                    </div>
                    <div class="dashboard-statslist2 width120">
                      {{ calculateUseRate(campaign.stats.impressions, campaign.stats.fills) }}
                    </div>
                    <div class="dashboard-statslist2 width120">
                      {{ calculateErrorRate(campaign.stats.tagRequests, campaign.stats.errors) }}
                    </div>
                  </div>
                  <div class="dashboard-statslist3">
                    <div class="dashboard-switch">
                      <input v-bind:id="'campaign' + campaign.id" type="checkbox" v-on:change="activateCampaign(campaign.id, $event)" class="cmn-toggle cmn-toggle-round-flat cmn-togglechange" v-bind:checked="campaign.active">
                      <label v-bind:for="'campaign' + campaign.id" class="cmn-labelchange"></label>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </section>
        </div>
        <div>
          <!-- START BACKFILL TAB -->
          <input name="tagmanage-tabbed" id="tagmanage-tabbed16" type="radio">
          <section>
            <h1>
              <label for="tagmanage-tabbed16">BACKFILL</label>
            </h1>
            <div>
              <div class="userinfo-websitesheader">
                <div class="userinfo-websitestitle">BACKFILL SETUP</div>

                <div class="userinfo-backfilldelete" @click="deleteBackfill" v-bind:class="{ noOpacity: selectedBackfill.length > 0 }">DELETE</div>
                <div class="userinfo-backfillcreate" @click="createNewBackfill(true)">CREATE BACKFILL</div>
              </div>
              <ul class="userinfo-itemlistheader">
                <li style="width: 250px;">URL</li>
                <li style="border-right:1px solid #E3E1E0">IMPRESSIONS</li>
                <li style="border-right:1px solid #E3E1E0">REVENUE</li>
                <li>ECPM</li>
                <li>AD TYPE</li>
                <li style="width:115px;border-right:1px solid #E3E1E0;">PLATFORM</li>
                <li style="width:117px;border-right:1px solid #E3E1E0;">WIDTH</li>
                <li style="width:calc(100% - 1098px)">BACKFILL</li>
                <li style="width:159px;border-left:0;">STATE</li>
              </ul>
              <!-- START BACKFILL WEBSITES LIST -->
              <ul class="admindashboard-dailystatslist userinfo-statslist userinfo-backfilllist">
                <li style="height: auto !important;" v-for="b in backfill">
                  <div style="cursor: pointer;">
                    <div class="dashboard-statslist1" style="width: 250px;">
                      <div class="tagcreate-checkwrap">
                        <input type="checkbox" id="check-onscroll" v-bind:id="'deleteBackfill' + b.id" v-bind:value="b.id" v-model="selectedBackfill">
                        <label v-bind:for="'deleteBackfill' + b.id"></label>
                      </div>
                      <div @click="editBackfill(b)" style="width: 250px;">
                        {{ b.website_domain }}
                      </div>
                    </div>
                    <div class="dashboard-statslist2" @click="editBackfill(b)">N/A</div>
                    <div class="dashboard-statslist2" @click="editBackfill(b)">N/A</div>
                    <div class="dashboard-statslist2" @click="editBackfill(b)">${{ b.ecpm }}</div>
                    <div class="dashboard-statslist2" @click="editBackfill(b)">{{ b.adType.data.name }}</div>
                    <div class="dashboard-statslist2" @click="editBackfill(b)" style="width:113px;border-right:0;">{{ b.platform_type }}</div>
                    <div class="dashboard-statslist2" @click="editBackfill(b)" style="width:119px;">{{ b.width }}</div>
                    <div class="dashboard-statslist2" @click="editBackfill(b)" style="width:calc(100% - 1098px);border-right:1px solid #e3e1e0;">
                      {{ b.advertiser }}
                    </div>
                  </div>
                  <div class="dashboard-statslist3">
                    <div class="dashboard-switch">
                      <input v-bind:id="'backfill' + b.id" type="checkbox" v-on:change="activateBackfill(b.id, $event)" class="cmn-toggle cmn-toggle-round-flat cmn-togglechange" v-bind:checked="b.active">
                      <label v-bind:for="'backfill' + b.id" class="cmn-labelchange"></label>
                    </div>
                  </div>
                </li>
              </ul>
              <!-- END ACCOUNT WEBSITES LIST -->
            </div>
          </section>
        </div>
        <div>
          <!-- START NOTES TAB -->
          <input name="tagmanage-tabbed" id="tagmanage-tabbed17" type="radio">
          <section>
            <h1>
              <label for="tagmanage-tabbed17">NOTES</label>
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
        websiteDateFilter: 'thisMonth',
        campaignsDateFilter: 'thisMonth',
        selectedBackfill: [],
        showBackfillForm: false,
        currentBackfill: User.defaultBackfill(),

        showBankInfo: false,
        note: '',
        stats: [],
        chartData: []
      }
    },

    methods: {
      addNewWebsite() {
        let domain = window.prompt('Enter the website\'s domain below:', 'domain.com')

        this.$store.dispatch('admin/addWebsiteToUser', { userId: this.account.id, domain: domain })
      },

      impersonate() {
        this.$store.dispatch('users/impersonate', this.account.id)
      },

      activateWebsite(id, event) {
        Admin.activateWebsite(id, event.target.checked)
      },

      ownedWebsite(id, event) {
        Admin.ownedWebsite(id, event.target.checked)
      },

      activateCampaign(id, event) {
        Admin.activateCampaign(id, event.target.checked)
      },

      activateBackfill(id, event) {
        User.activateBackfill(id, event.target.checked)
      },

      createNewBackfill() {
        this.currentBackfill = User.defaultBackfill()
        this.backfillFormVisible(true)
      },

      editBackfill(backfill) {
        this.currentBackfill = backfill
        this.backfillFormVisible(true)
      },

      deleteBackfill() {
        User.deleteBackfill(this.selectedBackfill).then(response => {
          this.$store.dispatch('admin/loadAccounts')
        })
      },

      goBack() {
        this.$router.push({ name: 'admin.accounts' })
      },

      addNote() {
        this.$store.dispatch('admin/addNote', { account: this.account, note: this.note })
      },

      websiteApprovedStatus(website) {
        if (website.waiting) {
          return 'waiting'
        }

        if (website.approved) {
          return 'approved'
        } else {
          return 'denied'
        }
      },

      activateAccount(id, status) {
        this.$store.dispatch('admin/activateUser', {
          id: id,
          status: status
        })
        let statusInfo = status ? 'activated' : 'deactivated'
        window.alert('Account ' + statusInfo)
      },

      websiteApprovedClass(website) {
        return 'website' + this.websiteApprovedStatus(website)
      },

      totalWebsiteImpressions(websites) {
        return _.sumBy(websites, (website) => {
          return website.stats.impressions
        })
      },

      playerTypeShort(playerType) {
        let short = {
          'onscrolldisplay': 'On-Scroll',
          'sidebarinfinity': 'Infinity'
        }

        if (short[playerType]) {
          return short[playerType] ? short[playerType] : playerType
        }
      },

      backfillFormVisible(visible) {
        this.showBackfillForm = visible
      },

      saveBackfillForm() {
        let duplicated = this.backfill.filter(backfill => {
          if (backfill.id === this.currentBackfill.id) {
            return false
          }

          this.currentBackfill.ad_type_id = parseInt(this.currentBackfill.ad_type_id)
          this.currentBackfill.website_id = parseInt(this.currentBackfill.website_id)

          if (backfill.ad_type_id === this.currentBackfill.ad_type_id &&
              backfill.platform_type === this.currentBackfill.platform_type &&
              backfill.website_id === this.currentBackfill.website_id) {
            return true
          }

          return false
        })

        if (duplicated.length > 0) {
          window.alert('Duplicated backfill')
          return
        }

        User.saveBackfill(this.currentBackfill).then(response => {
          this.$store.dispatch('admin/loadAccounts')
          this.backfillFormVisible(false)
        })
      },

      ...stats
    },

    computed: {
      account() {
        let account = _.find(this.$store.state.admin.accounts, { 'id': parseInt(this.$route.params.accountId) })

        if (account === undefined) {
          this.$store.dispatch('admin/loadAccounts')
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

        this.$store.dispatch('admin/loadWebsitesStats', { account: account, range: this.websiteDateFilter })
        this.$store.dispatch('admin/loadCampaignsStats', { account: account, range: this.campaignsDateFilter })
        return account
      },

      backfill() {
        let backfill = []

        if (this.account.websites === undefined) {
          return backfill
        }

        this.account.websites.data.map(website => {
          website.backfill.data.map(websiteBackfill => {
            websiteBackfill = _.cloneDeep(websiteBackfill)
            websiteBackfill['website_domain'] = website.domain
            backfill.push(websiteBackfill)
          })
        })

        return backfill
      },

      websites() {
        return _.sortBy(this.$store.state.admin.websitesStats, w => {
          return w.created_at
        })
      },

      campaigns() {
        return this.$store.state.admin.campaignStats
      }
    },

    watch: {
      websites: function(newWebsites) {
        this.$store.dispatch('admin/loadWebsitesStats', { account: this.account, range: this.websiteDateFilter })
      }
    },

    components: {
      AccountChart
    }
  }
</script>

<style lang="scss">
  .addnewwebsitebutton {
    float: right;
    margin-right: 20px;
    text-align: center;
    font-size: 11px;
    line-height: 30px;
    font-weight: 600;
    color: #FFFFFF;
    background: #3F485D;
    width: 150px;
    height: 30px;
    cursor: pointer;
    margin-top: 10px;
  }

  ul.userinfo-websitelistheader li:first-child, ul.userinfo-websitelist li .dashboard-statslist1 {
    width: calc(100% - 400px);
  }

  ul.userinfo-addtllinks li {
    width: auto;
    padding-left: 10px;
    padding-right: 10px;
  }

  .account_address {
    white-space: pre-wrap;
  }

  .userinfo-returntosearch, .userinfo-commentsubmitnote {
    cursor: pointer;
    cursor: hand;
  }

  .userActive {
    background: url('/images/useractivateon.png') 0 -22px !important;
  }

  .userInactive {
    background: url('/images/useractivateoff.png') 0 -22px !important;
  }

  .noOpacity {
    opacity: 1 !important;
  }
</style>
