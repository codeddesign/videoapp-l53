<template>
  <div id="campaign-listing">
    <app-modal 
        :visible="modal.visible"
        :title="modal.title"
        :body="modal.body"
        :confirm="modal.confirm"
        :callback="modal.callback"
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

            <div class="campaignview-dropbutton" @click="toggleAdvancedSearch">advanced search</div>
            <div class="campaignview-droppedarea" v-if="advancedSearch">
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
              <li>CREATED</li>
              <li>eCPM</li>
              <li>VIDEO PLAYS</li>
              <li>REVENUE</li>
              <li>CODE</li>
              <li>DELETE</li>
              <li>EDIT</li>
            </ul>
            <ul class="campaigngrid">
              <li v-for="campaign in filteredCampaigns">
                <div class="camplist-data1">{{ campaign.id }}</div>
                <div class="camplist-data2">{{ campaign.name }}</div>
                <div class="camplist-data3">{{ campaign.created_at_humans }}</div>
                <div class="camplist-data4">n/a</div>
                <div class="camplist-data5">n/a</div>
                <div class="camplist-data6">$ n/a</div>
                <div class="camplist-data7">
                  <a href="javascript:;" @click.prevent.default="embedCode(campaign)">
                    <div class="embedcode_icon"></div>
                  </a>
                </div>
                <div class="camplist-data8">
                  <a href="javascript:;" @click.prevent.default="deleteCampaign(campaign)">
                    <div class="remove_icon"></div>
                  </a>
                </div>
                <div class="camplist-data9">
                  <a href="javascript:;">
                    <div class="edit_icon"></div>
                  </a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <nav>
        <ul class="pager">
          <li v-show="pagination.previous" class="previous">
            <a @click="paginate('previous')" class="page-scroll">Previous</a>
          </li>
          <li v-show="pagination.next" class="next">
            <a @click="paginate('next')" class="page-scroll">Next</a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<style>
  .pager li > a {
    color: #23527c;
  }
  .pager li > a:hover, .pager li > a:focus {
    text-decoration: none;
    background-color: #eeeeee;
    cursor: pointer;
  }
</style>

<script>
  import AppModal from '../../components/AppModal.vue'
  import Fuse from 'fuse.js'

  export default {
    data() {
      return {
        search: '',
        advancedSearch: false,
        response: {
          campaigns: []
        },
        pagination: {
          page: 1,
          per_page: '',
          total: '',
          previous: false,
          next: false
        },
        startDate: null,
        endDate: null,
        modal: {
          visible: false,
          title: '',
          body: '',
          confirm: false,
          callback: () => {}
        }
      }
    },

    mounted() {
      this.$nextTick(function() {
        this.$http.get('/api/campaigns?page=' + this.pagination.page).then((response) => {
          this.response.campaigns = response.data.data
          this.pagination.page = response.data.page
          this.pagination.per_page = response.data.per_page
          this.pagination.total = response.data.total
          if (response.data.per_page < response.data.total) {
            this.pagination.next = true
          }
        })
      })
    },

    methods: {
      closeModal() {
        this.modal.visible = false
      },
      paginate(direction) {
        if (direction === 'previous') {
          --this.pagination.page
        } else if (direction === 'next') {
          ++this.pagination.page
        }
        this.$http.get('/api/campaigns?page=' + this.pagination.page).then((response) => {
          this.$set(this.response, 'campaigns', response.data.data)
          this.pagination.page = response.data.page
          this.pagination.per_page = response.data.per_page
          this.pagination.total = response.data.total
          if (response.data.page < response.data.total / response.data.per_page) {
            this.pagination.next = false
          }
          if (response.data.page === 1) {
            this.pagination.previous = false
          }
          if (response.data.page > 1 && response.data.page < response.data.total) {
            this.pagination.previous = true
          }
        })
      },

      toggleAdvancedSearch() {
        this.advancedSearch = !this.advancedSearch
      },

      deleteCampaign(campaign) {
        this.modal = {
          visible: true,
          title: 'Confirm',
          body: 'Are you sure you want to remove "' + campaign.name + '"?',
          confirm: true,
          callback: () => {
            this.$http.delete('/api/campaigns/' + campaign.id)
            .then(function() {
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
          body: '<textarea style="width: 100%;height: 100%;resize: none;min-width: 450px;"><script src="http://a3m.io:8000/p' + campaign.id + '.js"><\/script><\/textarea>'
        }
      }
    },

    computed: {
      filteredCampaigns() {
        if (this.search === '') {
          return this.response.campaigns
        }

        var options = {
          keys: [
            'name',
            'created_at_humans'
          ]
        }

        var fuse = new Fuse(this.response.campaigns, options)

        return fuse.search(this.search)
      }
    },

    components: {
      AppModal
    }
  }
</script>
