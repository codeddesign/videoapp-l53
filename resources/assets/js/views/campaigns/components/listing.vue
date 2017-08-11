<template>
  <div id="campaign-listing">
    <app-modal
        :visible="modal.visible"
        :title="modal.title"
        :body="modal.body"
        :confirm="modal.confirm"
        :callback="modal.callback"
        :message="modal.message"
        v-on:close="closeModal"
    ></app-modal>
    <div class="page-index" v-cloak>
      <div class="campaignselection-wrap">
        <div class="campaignview-wrap">
          <form @submit.prevent="">
              <input class="campaignview-search" name="all_search" id="all_search" placeholder="Search for.." v-model="search">
          </form>

          <form action="#" method="post">
            <div class="campaignview-searchicon"></div>

            <div class="campaignview-dropbutton" v-on:click="toggleAdvancedSearch">advanced search</div>
            <div class="campaignview-droppedarea" v-show="advancedSearch">
              <div class="campview-dropwhere">
                <div class="campview-droptitle">WHERE</div>
                <select name="ad_campaign_select">
                  <option value="campaign_name">Campaign Name</option>
                  <option value="video_rpm">RPM</option>
                </select>
                <div class="campview-selectarrow"></div>
              </div>
                <div class="campview-dropsearchfor">
                  <div class="campview-droptitle">SEARCH FOR</div>
                  <div class="campview-searchinput">
                    <input type="text" name="ad_campaign_value">
                    <div class="campview-searchinputicon"></div>
                  </div>
                </div>
                <div class="campview-dropandwhere">
                  <div class="campview-droptitle">WHERE</div>
                  <select name="video_rev_select">
                    <option value="video_plays">Video Plays</option>
                    <option value="revenue">Revenue</option>
                  </select>
                  <div class="campview-selectarrow"></div>
                </div>
                <div class="campview-dropmin">
                  <div class="campview-droptitle">MIN</div>
                  <input type="text" name="min">
                </div>
                <div class="campview-droptomax">
                  <div class="campview-droptitle">MAX</div>
                  <input type="text" name="max">
                </div>
                <button>SEARCH</button>
              </div>
          </form>

          <div class="campview-camplistwrap">
            <ul class="campaigngrid-title">
              <li>CAMPAIGN ID</li>
              <li>CAMPAIGN REFERENCE</li>
              <li style="width: 120px">CREATED</li>
              <li style="width: 120px">AD TYPE</li>
              <li>eCPM</li>
              <li style="width: 120px">VIDEO IMPR</li>
              <li style="width: 120px">DISPLAY IMPR</li>
              <li style="width: 120px">REVENUE</li>
              <li style="width: 75px">CODE</li>
              <li style="width: 75px">DELETE</li>
            </ul>
            <ul class="campaigngrid">
              <li v-for="campaign in filteredCampaigns">
                <div class="camplist-data1">{{ campaign.id }}</div>
                <div class="camplist-data2">{{ campaign.name }}</div>
                <div class="camplist-data3">{{ campaign.created_at_humans }}</div>
                <div class="camplist-data4">{{ campaign.ad_type_name }}</div>
                <div class="camplist-data5">
                  {{ formatMoney(calculateEcpm(campaign.stats.impressions, campaign.stats.revenue, false)) }}
                </div>
                <div class="camplist-data6">{{ formatNumber(campaign.stats.impressions) }}</div>
                <div class="camplist-data7">
                  {{ formatNumber(campaign.stats.desktopBackfillImpressions + campaign.stats.mobileBackfillImpressions) }}
                </div>
                <div class="camplist-data8">
                  {{ formatMoney(campaign.stats.revenue + campaign.stats.desktopBackfillRevenue + campaign.stats.mobileBackfillRevenue) }}
                </div>
                <div class="camplist-data9">
                  <a href="javascript:;" @click.prevent.default="embedCode(campaign)">
                    <div class="embedcode_icon"></div>
                  </a>
                </div>
                <div class="camplist-data10">
                  <a href="javascript:;" @click.prevent.default="deleteCampaign(campaign)">
                    <div class="remove_icon"></div>
                  </a>
                </div>
              </li>
            </ul>
            <div class="understatlist-wrapper">
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
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import AppModal from '../../components/AppModal.vue'
  import Fuse from 'fuse.js'
  import Pagination from '../../../services/pagination'
  import stats from '../../../services/stats'
  import http from '../../../services/http'
  import numeral from 'numeral'
  import accounting from 'accounting'

  export default {
    name: 'Listing',
    data() {
      return {
        search: '',
        advancedSearch: false,
        response: {
          campaigns: []
        },
        pagination: new Pagination(),
        startDate: null,
        endDate: null,
        modal: {
          visible: false,
          title: '',
          body: '',
          confirm: false,
          message: false,
          callback: () => {}
        }
      }
    },

    mounted() {
      this.$nextTick(function() {
        http.get('/campaigns')
            .then((response) => {
              this.response.campaigns = response.data.data
            })
      })
    },

    methods: {
      closeModal() {
        this.modal.visible = false
      },

      toggleAdvancedSearch() {
        this.advancedSearch = !this.advancedSearch
      },

      deleteCampaign(campaign) {
        this.modal = {
          visible: true,
          title: 'Confirm',
          body: 'Are you sure you want to remove "' + campaign.name + '"?',
          confirm: 'Remove',
          callback: () => {
            return http.delete('/campaigns/' + campaign.id)
                .then((response) => {
                  var index = this.response.campaigns.indexOf(campaign)
                  this.response.campaigns.splice(index, 1)

                  this.closeModal()
                })
          }
        }
      },

      embedCode(campaign) {
        this.modal = {
          visible: true,
          title: 'Copy the code below into your website',
          body: '<textarea style="width: 100%;height: 100%;resize: none;min-width: 450px;" id="tt">' + campaign.embed + '<\/textarea>',
          confirm: 'Copy embed',
          message: '',
          callback: () => {
            return new Promise((resolve, reject) => {
              document.querySelector('#tt').select()
              document.execCommand('copy')

              this.modal.message = 'Embed code has been copied to your clipboard..'

              setTimeout(() => {
                this.modal.message = ''
              }, 2500)

              resolve()
            })
          }
        }
      },

      formatNumber(number) {
        return numeral(number).format('0,0')
      },

      formatMoney(number) {
        return accounting.formatMoney(number)
      },
      ...stats
    },

    computed: {
      filteredCampaigns() {
        let filteredCampaigns = this.response.campaigns

        if (this.search !== '') {
          var options = {
            keys: [
              'name',
              'created_at_humans'
            ]
          }

          var fuse = new Fuse(this.response.campaigns, options)

          filteredCampaigns = fuse.search(this.search)
        }

        this.pagination.data = filteredCampaigns
        return this.pagination.getData()
      }
    },

    components: {
      AppModal
    }
  }
</script>
