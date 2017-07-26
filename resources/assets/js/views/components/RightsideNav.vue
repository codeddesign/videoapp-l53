<template>
  <div class="rightside-nav">
    <div class="rightside-navlefttitle">
      {{ title }}
    </div>
    <!-- ACCOUNT DETAILS -->
    <div id="accountdetails" class="rightside-navdropdown">ACCOUNT DETAILS <span></span></div>
    <ul id="accountdetails-navdroparea" class="rightside-navdroparea" style="display:none;">
        <router-link :to="{ name: 'user.settings'}" tag="li">
          EDIT ACCOUNT
        </router-link>
        <router-link :to="{ name: 'logout'}" tag="li">
          LOGOUT
        </router-link>
    </ul>

    <!-- AVAILABLE WEBSITES
    <div id="availablesites" class="rightside-navdropdown">WEBSITES <span></span></div>
    <ul id="availablesites-navdroparea" class="rightside-navdroparea" style="display:none;">
      <a href="#">
        <li>SITE.COM</li>
      </a>
      <a href="#">
        <li>ABC.COM</li>
      </a>
    </ul>-->
  </div>
</template>

<script>
  export default {
    name: 'Rightside-Nav',
    computed: {
      title() {
        return this.$route.meta.title
      }
    }
  }

  import $ from 'jquery'

  $(document).ready(function() {
    // WEBSITE LIST DROPDOWN
    $('#availablesites, #availablesites-navdroparea').mouseover(function() {
      $('#availablesites-navdroparea').show()
      $('#availablesites').css('background', '#303749')
      // $('#availablesites').css('border-right', '1px solid #303749');
    })

    $('#availablesites, #availablesites-navdroparea').mouseout(function() {
      $('#availablesites-navdroparea').hide()
      $('#availablesites').css('background', 'transparent')
        // $('#availablesites').css('border-right', '1px solid #5C5882');
    })

    // ACCOUNT DETAILS DROPDOWN
    $('#accountdetails, #accountdetails-navdroparea').mouseover(function() {
      $('#accountdetails-navdroparea').show()
      $('#accountdetails').css('background', '#303749')
      $('#accountdetails').css('border-left', '1px solid #303749')
    })

    $('#accountdetails, #accountdetails-navdroparea').mouseout(function() {
      $('#accountdetails-navdroparea').hide()
      $('#accountdetails').css('background', 'transparent')
      $('#accountdetails').css('border-left', '1px solid #5C5882')
    })
  })
</script>

<style lang="scss">
.rightside-nav {
  float: left;
  width: 100%;
  height: 50px;
  background: #6C689E;
}

.rightside-navlefttitle {
  float: left;
  font-size: 11px;
  color: #FFFFFF;
  line-height: 50px;
  margin-left: 34px;
  letter-spacing: 0.6px;
  text-transform: uppercase;
}

.rightside-navdropdown {
  float: right;
  width: 178px;
  border-left: 1px solid #5C5882;
  text-align: center;
  color: #FFFFFF;
  font-size: 12px;
  line-height: 50px;
  cursor: pointer;
}

.rightside-navdropdown:hover {
  background: #303749;
  border-left: 1px solid #303749;
}

.rightside-navdropdown span {
  background: url('/images/navdropdownspan.png');
  width: 8px;
  height: 4px;
  float: right;
  margin-top: 22px;
  margin-right: 17px;
}
</style>
