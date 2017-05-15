<template>
  <div>
    <input name="tagmanage-tabbed" id="tagmanage-tabbed1" type="radio">
    <section>
      <h1>
        <label for="tagmanage-tabbed1">MANAGE TAGS</label>
      </h1>
      <div>
        <tag-form v-if="formShowStatus"></tag-form>

        <!-- START TAGS AREA -->
        <div class="tagmanage-addnewtag" @click="showForm()">ADD NEW TAG</div>

        <!-- EDIT TAGS PLATFORM SELECT -->
        <div class="edittagsselect-wrapper" style="margin-top:11px;">
            <div class="edittagsselect-title">Type:</div>
            <select v-model="filters['type']">
              <option value="all">All</option>
              <option value="instream">Instream</option>
              <option value="outstream">Outstream</option>
            </select>
            <div class="edittagsselect-selectarrow" style="margin-left:118px;"></div>
          </div>

          <!-- EDIT TAGS TYPE SELECT -->
          <div class="edittagsselect-wrapper" style="margin-right:20px;margin-top:11px;">
            <div class="edittagsselect-title">Platform:</div>
            <select v-model="filters['platform']">
              <option value="all">All</option>
              <option value="desktop">Desktop</option>
              <option value="mobile">Mobile</option>
            </select>
            <div class="edittagsselect-selectarrow"></div>
          </div>

        <!-- CAMPAIGN SELECTION AREA -->
          <ul class="dashboard-dailystatstitles dashboard-tagsedit dashboard-tagsedittitle">
              <li style="width:16%;">ADVERTISER</li>
              <li style="width:19.5%;">TAG NAME</li>
              <li>PLATFORM</li>
              <li>TYPE</li>
              <li>TIMEOUT</li>
              <li>WRAPPERS</li>
              <li>DELAY</li>
              <li>STATE</li>
          </ul>
          <ul class="admindashboard-dailystatslist dashboard-tagsedit">
            <li v-for="tag in tags" v-bind:title="'ID: ' + tag.id">
              <div @click="showForm(tag)">
                <div class="dashboard-statslist1">{{ tag.advertiser }} ({{tag.priority_count}})</div>
                <div class="dashboard-statslist2">{{ tag.description }}</div>
                <div class="dashboard-statslist2">{{ tag.platform_type }}</div>
                <div class="dashboard-statslist2">{{ tag.type }}</div>
                <div class="dashboard-statslist2">{{ tag.timeout_limit }}</div>
                <div class="dashboard-statslist2">{{ tag.wrapper_limit }}</div>
                <div class="dashboard-statslist2">{{ tag.delay_time }}</div>
              </div>
              <div class="dashboard-statslist3" >
                <div class="dashboard-switch">
                  <input v-bind:id="'activate' + tag.id" v-on:change="activateTag(tag.id, $event)" class="cmn-toggle cmn-toggle-round-flat cmn-togglechange" type="checkbox" v-bind:checked="tag.active">
                  <label v-bind:for="'activate' + tag.id" class="cmn-labelchange"></label>
                </div>
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
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
              </select>
              <div class="dashpagerows-selectarrow"></div>
            </div>
          </div>
        <!-- END TAGS AREA -->
      </div>
    </section><!-- END EDIT TAGS -->
  </div>
</template>

<script>
  import TagForm from './TagForm.vue'
  import Pagination from '../../../services/pagination'
  import Tag from '../../../models/tag'

  export default {
    name: 'EditTags',

    data() {
      return {
        filters: {
          platform: 'all',
          type: 'all'
        },

        pagination: new Pagination()
      }
    },

    computed: {
      formShowStatus() {
        return this.$store.state.admin.showTagForm
      },

      tags() {
        let tags = this.$store.state.admin.tags

        if (this.filters.platform !== 'all') {
          tags = tags.filter((tag) => {
            return tag.platform_type === this.filters.platform
          })
        }

        if (this.filters.type !== 'all') {
          tags = tags.filter((tag) => {
            return tag.ad_type === this.filters.type
          })
        }

        this.pagination.data = tags
        return this.pagination.getData()
      }
    },

    mounted() {
      this.$store.dispatch('admin/loadTags')
      this.$store.dispatch('admin/loadWebsites')
      this.$store.dispatch('admin/loadCountries')
    },

    methods: {
      showForm(tag = null) {
        if (tag === null) {
          tag = Tag.default()
        }

        this.$store.dispatch('admin/setCurrentTag', tag)
        this.$store.dispatch('admin/setShowTagForm', true)
      },

      activateTag(id, event) {
        this.$store.dispatch('admin/activateTag', {
          id: id,
          status: event.target.checked
        })
      }
    },

    components: {
      TagForm
    }
  }
</script>
