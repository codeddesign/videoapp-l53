<template>
  <div>
    <div class="accountcreation-wrapper">
        <!-- BASIC INFORMATION -->
        <div class="accountcreation-block">
          <div class="accountcreation-toptitle">BASIC INFORMATION</div>
          <div class="accountcreation-white">
            <div class="accountcreation-inputhalf">
              <div class="accountcreation-inputtitle">FIRST NAME</div>
              <input placeholder="Your Name.." name="First Name" v-model="account.first_name" v-validate data-vv-rules="required">
            </div>
            <div class="accountcreation-inputhalf">
              <div class="accountcreation-inputtitle">LAST NAME</div>
              <input placeholder="Your Name.." name="Last Name" v-model="account.last_name" v-validate data-vv-rules="required">
            </div>
            <div class="accountcreation-inputhalf">
              <div class="accountcreation-inputtitle">COMPANY NAME</div>
              <input placeholder="Company.." name="Company" v-model="account.company" v-validate data-vv-rules="required">
            </div>
            <div class="accountcreation-inputhalf">
              <div class="accountcreation-inputtitle">EMAIL ADDRESS</div>
              <input placeholder="Where to email you.." name="Email" v-model="account.email" v-validate data-vv-rules="required">
            </div>
            <div class="accountcreation-inputhalf">
              <div class="accountcreation-inputtitle">TELEPHONE #</div>
              <input placeholder="How can we call you.." name="Telephone" v-model="account.phone_number" v-validate data-vv-rules="required">
            </div>
          </div>
        </div>
        <!-- END BASIC INFORMATION -->
        <!-- ADDRESS -->
        <div class="accountcreation-block">
          <div class="accountcreation-toptitle">ADDRESS</div>
          <div class="accountcreation-white">
            <div class="accountcreation-inputfull">
              <div class="accountcreation-inputtitle">STREET (LINE 1)</div>
              <input placeholder="Street Address.." v-model="account.street_line_1">
            </div>
            <div class="accountcreation-inputfull">
              <div class="accountcreation-inputtitle">STREET (LINE 2)</div>
              <input placeholder="Street Line 2.." v-model="account.street_line_2">
            </div>
            <div class="accountcreation-inputhalf" style="clear:left;">
              <div class="accountcreation-inputtitle">CITY</div>
              <input placeholder="City.." v-model="account.city">
            </div>
            <div class="accountcreation-inputhalf">
              <div class="accountcreation-inputtitle">STATE / PROVINCE</div>
              <input placeholder="State.." v-model="account.state">
            </div>
            <div class="accountcreation-inputhalf">
              <div class="accountcreation-inputtitle">ZIP CODE</div>
              <input placeholder="Zip Code.." v-model="account.zip_code">
            </div>
            <div class="accountcreation-inputhalf">
              <div class="accountcreation-inputtitle">COUNTRY</div>
              <input placeholder="Country.." v-model="account.country">
            </div>
          </div>
        </div>
        <!-- END ADDRESS -->
        <!-- BANK DETAILS -->
        <div class="accountcreation-block" v-if="account.id">
          <div class="accountcreation-toptitle">BANK DETAILS</div>
          <div class="accountcreation-white">
            <div class="accountcreation-inputhalf">
              <div class="accountcreation-inputtitle">NAME ON ACCOUNT</div>
              <input placeholder="Name on Account.." v-model="account.bank_details.account_name">
            </div>
            <div class="accountcreation-inputhalf">
              <div class="accountcreation-inputtitle">BANK NAME</div>
              <input placeholder="Your Banks Name.." v-model="account.bank_details.bank_name">
            </div>
            <div class="accountcreation-textareafull">
              <div class="accountcreation-inputtitle">BANK ADDRESS</div>
              <textarea placeholder="Address.." v-model="account.bank_details.bank_address"></textarea>
            </div>
            <div class="accountcreation-inputhalf" style="clear:left;">
              <div class="accountcreation-inputtitle">ACCOUNT #</div>
              <input placeholder="123456789.." v-model="account.bank_details.account_number">
            </div>
            <div class="accountcreation-inputhalf">
              <div class="accountcreation-inputtitle">ROUTING #</div>
              <input placeholder="000123456789.." v-model="account.bank_details.routing_number">
            </div>
          </div>
        </div>
        <!-- END BANK DETAILS -->
        <!-- CHANGE PASSWORD -->
        <div class="accountcreation-block" v-if="account.id">
          <div class="accountcreation-toptitle">CHANGE PASSWORD</div>
          <div class="accountcreation-white">
            <div class="accountcreation-inputhalf">
              <div class="accountcreation-inputtitle">CURRENT PASSWORD</div>
              <input type="password" v-model="account.current_password" placeholder="Old Password..">
            </div>
            <div class="accountcreation-inputhalf" style="clear:left;">
              <div class="accountcreation-inputtitle">NEW PASSWORD</div>
              <input type="password" v-model="account.password" placeholder="New Password..">
            </div>
            <div class="accountcreation-inputhalf">
              <div class="accountcreation-inputtitle">CONFIRM PASSWORD</div>
              <input type="password" v-model="account.password_confirmation" placeholder="Confirm Password..">
            </div>
          </div>
        </div>
        <!-- END CHANGE PASSWORD -->
        <!-- WEBSITES -->
        <div class="accountcreation-block" v-if="! account.id">
          <div class="accountcreation-toptitle">WEBSITES</div>
          <div class="accountcreation-white">
            <ul class="accountcreation-websitewrap">
              <li v-for="(website, index) in account.websites">
                <div class="accountcreation-websiteinput">
                  <div class="accountcreation-inputtitle" v-if="index === 0">WEBSITE URL</div>
                  <input placeholder="Website.." v-model="website.url">
                </div>
                <div class="accountcreation-websiteoo">
                  <div class="accountcreation-inputtitle" v-if="index === 0">O&O</div>
                  <div class="tagcreate-selectwrap">
                  <select class="tagcreate-dropdown" v-model="website.owned">
                    <option value="false">No</option>
                    <option value="true">Yes</option>
                  </select>
                  <div class="tagcreate-selectarrow"></div>
                </div>
                </div>
                <div class="accountcreation-webaddmore" @click="addWebsite()"></div>
              </li>
            </ul><!-- end accountcreation-websitewrap -->
          </div>
        </div>
        <!-- END WEBSITES -->
      </div><!-- end accountcreation-wrapper -->

      <!-- SAVE BUTTON -->
      <div class="tagcreate-savetagwrap" style="margin-top:0px;">
        <div class="tagcreate-savetagbutton" style="width:100%;" @click="save()">SAVE INFORMATION</div>
      </div>
      <!-- END SAVE BUTTON -->
    </div>
</template>

<script>
  import _ from 'lodash'
  import stats from '../../services/stats'
  import User from '../../models/user'

  export default {
    name: 'AccountForm',

    data() {
      return {
        account: {}
      }
    },

    methods: {
      loadAccount() {
        let accountData = this.currentUser

        this.currentUser.password = ''
        this.currentUser.password_confirmation = ''
        this.currentUser.current_password = ''

        this.account = _.cloneDeep(accountData)
      },

      save() {
        this.$validator.validateAll()

        if (this.errors.any()) {
          var errorMsg = this.errors.errors.map((error) => {
            return error.msg + '\n'
          })

          window.alert(errorMsg)
          return
        }

        User.update(this.account).then(response => {
          this.$store.dispatch('users/loadUser')
          this.$router.push({ name: 'dashboard' })
        }).catch((error) => {
          let errors = ''

          _.map(error.response.data, message => {
            errors += message[0]
            errors += '\n'
          })

          window.alert(errors)
        })
      },

      addWebsite() {
        this.account.websites.push({
          'url': '',
          'owned': false
        })
      },

      ...stats
    },

    computed: {
      currentUser() {
        return this.$store.state.users.currentUser
      }
    },

    mounted() {
      this.loadAccount()
      this.$watch('currentUser', (value) => {
        this.loadAccount()
      }, {
        deep: true
      })
    }
  }
</script>

<style lang="scss">
</style>
