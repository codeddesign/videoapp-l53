<template>
  <div class="selectadtype-overlay" v-cloak>
    <div id="adcreation-form">
      <div class="steps clearfix">
        <ul>
          <li v-for="(tab, index) in tabs" :class="{'current': step == tab.name, 'disabled': tab.disabled}" @click="toStep(index + 1)">
              <span class="number">{{ index + 1 }}.</span> {{ tab.title }}
          </li>
        </ul>
      </div>

      <!-- start select ad type -->
      <div class="adcreation-section" v-show="step == 'type'">
        <div class="selectadtype-title">Select your ad type to proceed:</div>
        <div class="selectadtype-wrapper">
          <ul class="selectadtype-adtypes">
            <li v-for="type in campaign_types" :class="{'disabled': !type.available}" @click="pickAdType(type)">
              <img :src="'/images/adtype-'+type.title.replace(/-|\s/g, '').toLowerCase()+'.png'">
              <div class="selectadtype-adtypetitle">{{ type.title }}</div>
              <div class="selectadtype-adtypeselect">select this ad</div>
            </li>
          </ul>
        </div>
      </div>
      <!-- end select ad type -->

      <!-- start create ad name -->
      <div class="adcreation-section" v-if="step == 'name'">
        <div class="selectadtype-title">
          {{ selectedCampaign.has_name ? 'Create a Reference Name for your Ad:' : 'Ad your youtube link' }}

          <div class="message error" v-if="error">
            {{ error }}
          </div>
        </div>
        <div class="selectadtype-wrapper">
          <div class="createcampaign-fulltoparea">
            <div class="campaign-creationwrap createcampaign-middlecreatewrap">
              <form name="campaignForm" @submit.prevent.default="checkPreview()">
                <div class="campaign-creationyoutube" v-if="!selectedCampaign.has_name">
                  <label for="campaign_name">Youtube</label>
                  <input id="campaign_name" type="text" placeholder="https://www.youtube.com/watch?v=AbcDe1FG234" required v-model="campaign.video">
                </div>

                <div class="campaign-creationyoutube" v-if="selectedCampaign.has_name">
                  <label for="campaign_name">NAME</label>
                  <div class="campaignform-error hidden">Already same title exists.</div>
                  <input id="campaign_name" type="text" placeholder="Reference name.." required v-model="campaign.name">
                </div>

                <button>PROCEED TO PREVIEW</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- end create ad name -->

      <div class="adcreation-section" v-show="step == 'preview'">
        <div class="selectadtype-title">
          <div v-if="!loading">Your video preview</div>
          <div v-else>Please wait..</div>
        </div>

        <div class="selectadtype-wrapper">
          <div class="createcampaign-fulltoparea">
            <div class="campaign-creationwrap createcampaign-middlecreatewrap preview" ref="previewContainer"></div>
          </div>
        </div>

        <div style="clear: both;color: white;padding-top: 20px;text-align: center;" v-show="!loading">
          <button @click="save()">Save</button>
        </div>
      </div>

      <div class="adcreation-section" v-show="step == 'code'">
        <div class="selectadtype-title">
          <div class="message success" v-if="!loading">
            Campaign "@{{ savedCampaign.name }}" is now saved
          </div>
          <div v-if="loading">Please wait..</div>
        </div>

        <div style="margin: 0 auto;" v-if="!loading">
          <div class="createcampaign-fulltoparea">
            <div class="campaign-creationwrap createcampaign-middlecreatewrap" style="background: none;">
              <label for="embed_js" class="white" style="font-size: 14px">Copy and paste the code below in your website:</label>
              <textarea id="embed_js" style="width: 100%;height: 100%;resize: none;" ref="embedJsCode" @click="selectEmbedText()"></textarea>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import _ from 'lodash'
  import http from '../../../services/http'

  export default {
    data() {
      return {
        campaign_types: null,
        step: 'type',
        tabNo: 0,
        loading: false,
        error: false,
        tabs: [
          {
            name: 'type',
            title: 'Select Ad Type',
            disabled: false
          }, {
            name: 'name',
            title: 'Create Ad Name',
            disabled: true
          }, {
            name: 'preview',
            title: 'Preview Campaign',
            disabled: true
          }, {
            name: 'code',
            title: 'Get Code',
            disabled: true
          }
        ],
        campaign: {
          campaign_type_id: false,
          name: '',
          video: ''
        },
        backup: {},
        savedCampaign: {}
      }
    },
    mounted() {
      this.$nextTick(function() {
        http.get('/campaign-types')
            .then((response) => {
              this.campaign_types = response.data.data
            })

        // hold a clean copy of campaign
        this.backup = JSON.parse(JSON.stringify(this.campaign))
      })
    },

    computed: {
      selectedCampaign: function() {
        return _.find(this.campaign_types, (type) => {
          return type.id === this.campaign.campaign_type_id
        })
      }
    },

    methods: {
      resetCampaign: function() {
        Object.keys(this.campaign).forEach(function(key) {
          this.campaign[key] = this.backup[key]
        }.bind(this))
      },

      nextStep: function(index) {
        index -= 1

        this.tabs[index].disabled = false

        this.step = this.tabs[index].name
      },

      toStep: function(index) {
        index -= 1

        var tab = this.tabs[index]

        if (index === 0) {
          this.resetCampaign()
        }

        this.tabs.forEach(function(tab, i) {
          if (i > index) {
            tab.disabled = true
          }
        })

        if (!tab.disabled) {
          this.step = tab.name
        }
      },
      pickAdType: function(type) {
        this.campaign.campaign_type_id = type.id

        this.nextStep(2)
      },
      addJSPreview: function(src) {
        var script

        if (!src) {
          this.$refs.previewContainer.innerHTML = ''

          return false
        }

        this.$refs.previewContainer.innerHTML = src

        var scripts = Array.prototype.slice.call(this.$refs.previewContainer.getElementsByTagName("script"));
        for (var i = 0; i < scripts.length; i++) {
            if (scripts[i].src != "") {
                var tag = document.createElement("script");
                tag.src = scripts[i].src;
                document.getElementsByTagName("head")[0].appendChild(tag);
            }
            else {
                eval(scripts[i].innerHTML);
            }
        }
      },
      checkPreview: function() {
        this.nextStep(3)

        this.loading = true
        this.error = false
        this.addJSPreview()

        http.post('/campaigns/store/preview', this.campaign)
            .then((response) => {
              this.loading = false

              this.addJSPreview(response.data.embed)
            })
            .catch((response) => {
              this.error = response.data.message

              this.toStep(2)
            })
      },
      save: function() {
        this.nextStep(4)

        this.loading = true

        http.post('/campaigns', this.campaign)
            .then((response) => {
              this.loading = false

              this.resetCampaign()

              this.tabs.forEach(function(tab, i) {
                if (i > 0 && i < 4) {
                  tab.disabled = true
                }
              })

              this.savedCampaign = response.data.campaign

              this.$nextTick(function() {
                this.$refs.embedJsCode.value = response.data.embed
              })
            })
      },

      selectEmbedText: function() {
        this.$refs.embedJsCode.select()
      }
    },

    filters: {
      capitalize: v => (v[0].toUpperCase() + v.slice(1))
    },
  }
</script>

<style scoped>
    .selectadtype-overlay li {
        cursor: pointer;
    }

    .selectadtype-overlay li.disabled {
        pointer-events: none;
        color: #4A5263 !important;
    }

    .selectadtype-overlay li.current {
        color: #FFFFFF !important;
    }

    .videosize {
        width: 90px !important;
    }

    .selectadtype-overlay button {
        background: #8883B9;
        text-align: center;
        display: inline-block;
        width: 200px;
        height: 46px;
        line-height: 46px;
        font-size: 12px;
        color: #FFFFFF;
        border: none;
        cursor: pointer;
    }

    .message {
        max-width: 650px;
        padding: 10px 0;
        margin: 0 auto;
    }

    .message.error {
        background: red;
    }

    .message.success {
        background: #4596CB;
    }

    label.white {
        float: left;
        width: 100%;
        font-size: 10px;
        color: white;
        font-weight: 600;
        margin-bottom: 9px;
    }
</style>
