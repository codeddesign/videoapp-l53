<template>
<div>
  <div class="display-dashboardtoparea">
    <div class="display-dashboardtimewrap">
      <div class="dashmaintime-title">Time Range</div>
      <div class="dashboard-mainselect">
        <select>
        <option value="">Today</option>
        <option value="">Yesterday</option>
        <option value="">Last 7 Days</option>
        <option value="">This Month</option>
        <option value="">Last Month</option>
      </select>
      <div class="dashmain-selectarrow"></div>
      </div>  
    </div>
    <a href="/campaign">
      <div class="currentcamp-createbutton">GENERATE REPORT</div>
    </a>    
  </div>
        
  <!-- TAG GRAPH AREA -->
  <div class="taggraph-wrapper">
    <div class="taggraph-wrapleft">
      <ul class="taggraph-selection">
        <li class="selected">Requests</li>
        <li class="selected">Fill</li>
        <li>Impressions</li>
        <li>Errors</li>
      </ul>
    </div>
    <div class="taggraph-wrapright">
      <ul class="taggraph-textlist">
        <li>
          <div class="taggraph-listtitle">REQUESTS:</div>
          <div class="taggraph-listnumber">30,404,357</div>
        </li>
        <li>
          <div class="taggraph-listtitle">FILLS:</div>
          <div class="taggraph-listnumber">101,593</div>
        </li>
        <li>
          <div class="taggraph-listtitle">IMPRESSIONS:</div>
          <div class="taggraph-listnumber">22,509</div>
        </li>
        <li>
          <div class="taggraph-listtitle">FILL-RATE:</div>
          <div class="taggraph-listnumber">1.35%</div>
        </li>
        <li>
          <div class="taggraph-listtitle">USE-RATE:</div>
          <div class="taggraph-listnumber">57.31%</div>
        </li>
        <li>
          <div class="taggraph-listtitle">ERRORS:</div>
          <div class="taggraph-listnumber">1,507,579</div>
        </li>
        <li>
          <div class="taggraph-listtitle">REVENUE:</div>
          <div class="taggraph-listnumber">$2,309</div>
        </li>
      </ul>
    </div>
  </div>
      
  <!-- BEGIN TABS -->
  <div class="tabmanage-headerbar"></div>
  <div class="tagmanage-tabbed">
    <input name="tagmanage-tabbed" id="tagmanage-tabbed1" type="radio" checked>
    <section>
      <h1>
        <label for="tagmanage-tabbed1">EDIT TAGS</label>
      </h1>
      <div><!-- START EDIT TAGS -->
        <div class="tagemanage-addnewtag">ADD NEW TAG</div>
        
        <!-- EDIT TAGS PLATFORM SELECT -->
        <div class="edittagsselect-wrapper">
          <div class="edittagsselect-title">Type:</div>
          <select>
            <option value="">All</option>
            <option value="">Instream</option>
            <option value="">Outstream</option>
          </select>
          <div class="edittagsselect-selectarrow" style="margin-left:118px;"></div>
        </div>
          
        <!-- EDIT TAGS TYPE SELECT -->
        <div class="edittagsselect-wrapper" style="margin-right:20px;">
          <div class="edittagsselect-title">Platform:</div>
          <select>
            <option value="">All</option>
            <option value="">Desktop</option>
            <option value="">Mobile</option>
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
          <li>
            <div class="dashboard-statslist1">AOL</div>
            <div class="dashboard-statslist2">AOL_Outstream_Desktop</div>
            <div class="dashboard-statslist2">DESKTOP</div>
            <div class="dashboard-statslist2">INSTREAM</div>
            <div class="dashboard-statslist2">8000</div>
            <div class="dashboard-statslist2"></div>
            <div class="dashboard-statslist2">2000</div>
            <div class="dashboard-statslist3">
              <div class="dashboard-switch">
                <input id="cmn-toggle-" class="cmn-toggle cmn-toggle-round-flat cmn-togglechange" type="checkbox" checked>
                <label for="cmn-toggle-" class="cmn-labelchange"></label>
              </div>
            </div>
          </li>
        </ul>
        <div class="understatlist-wrapper">
          <div class="dashpagination-wrapper">
            <div class="dashpag-left"></div>
            <div class="dashpag-numbers">1 of 12</div>
            <div class="dashpag-right"></div>
          </div>
          <div class="dashpagerows-wrapper">
            <div class="dashpagerows-title">Display Rows:</div>
            <select>
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
            </select>
            <div class="dashpagerows-selectarrow"></div>
          </div>
        </div>
          
      </div><!-- END EDIT TAGS -->
    </section>
    <input name="tagmanage-tabbed" id="tagmanage-tabbed2" type="radio">
    <section>
      <h1>
        <label for="tagmanage-tabbed2">COMPARE TAGS</label>
      </h1>
      <div>STILL BEING WORKED ON</div>
    </section>
    <input name="tagmanage-tabbed" id="tagmanage-tabbed3" type="radio">
    <section>
      <h1>
        <label for="tagmanage-tabbed3">GLOBAL OPTIONS</label>
      </h1>
      <div>STILL BEING WORKED ON</div>
    </section>
    </div>
    <!-- END TABS -->
  </div>
</div>
  
</template>

<script>
  export default {
    name: 'TagManagement',

  }
</script>
