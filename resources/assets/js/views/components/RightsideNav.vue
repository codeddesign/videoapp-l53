<template>
  <div class="rightside-nav">
    <div class="rightside-navlefttitle">
    </div>
    <!-- ACCOUNT DETAILS -->
    <div id="accountdetails" class="rightside-navdropdown">ACCOUNT DETAILS <span></span></div>
    <ul id="accountdetails-navdroparea" class="rightside-navdroparea" style="display:none;">
        <a href="#">
          <li>EDIT ACCOUNT</li>
        </a>
        <a href="/logout">
          <li>LOGOUT</li>
        </a>
    </ul>

    <!-- AVAILABLE WEBSITES -->
    <div id="availablesites" class="rightside-navdropdown">WEBSITES <span></span></div>
    <ul id="availablesites-navdroparea" class="rightside-navdroparea" style="display:none;">
      <a href="#">
        <li>SITE.COM</li>
      </a>
      <a href="#">
        <li>ABC.COM</li>
      </a>
    </ul>
  </div>
</template>

<script>
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
