<template>
  <div>
    <div class="display-dashboardtoparea">
      <div class="edittagsselect-wrapper comparetagsselect-wrapper">
        <div class="edittagsselect-title">Time Range</div>
        <select>
          <option value="today">
            Today
          </option>
          <option value="yesterday">
            Yesterday
          </option>
          <option value="twoDaysAgo">
            2 Days Ago
          </option>
          <option value="sevenDays">
            Last 7 Days
          </option>
          <option value="thisMonth">
            This Month
          </option>
          <option value="lastMonth">
            Last Month
          </option>
        </select>
        <div class="edittagsselect-selectarrow" style="margin-left: 158px;"></div>
      </div>
      <div class="edittagsselect-wrapper comparetagsselect-wrapper">
        <div class="edittagsselect-title">Website</div>
        <select>
          <option value="1">domain.com</option>
          <option value="1">domain.com</option>
          <option value="1">domain.com</option>
        </select>
        <div class="edittagsselect-selectarrow" style="margin-left: 138px;"></div>
      </div>
    </div>
    <!-- ANALYTICS STATS -->
    <!-- TOP ANALYTICS -->
    <ul class="campaignstats-row analyticsstats-row">
      <li>
          <div class="campaignstats-title">TOTAL IMPRESSIONS</div>
          <div class="campaignstats-digit">60,551</div>
      </li>
      <li>
          <div class="campaignstats-title">RETURNING</div>
          <div class="campaignstats-digit">3,762</div>
      </li>
      <li>
          <div class="campaignstats-title">UNIQUE</div>
          <div class="campaignstats-digit">$1,087</div>
      </li>
      <li>
          <div class="campaignstats-title">RETURNING %</div>
          <div class="campaignstats-digit">$6.70</div>
      </li>
      <li>
          <div class="campaignstats-title">UNIQUE %</div>
          <div class="campaignstats-digit">$6.70</div>
      </li>
    </ul>
    <!-- MIDDLE ANALYTICS -->
    <ul class="campaignstats-row analyticsstats-row">
        <li>
            <div class="campaignstats-title">VIDEO IMPRESSIONS</div>
            <div class="campaignstats-digit">60,551</div>
        </li>
        <li>
            <div class="campaignstats-title">BACKFILL IMPRESSIONS</div>
            <div class="campaignstats-digit">37%</div>
        </li>
        <li>
            <div class="campaignstats-title">DESKTOP IMPRESSIONS</div>
            <div class="campaignstats-digit">1%</div>
        </li>
        <li>
            <div class="campaignstats-title">TABLET IMPRESSIONS</div>
            <div class="campaignstats-digit">90%</div>
        </li>
        <li>
            <div class="campaignstats-title">MOBILE IMPRESSIONS</div>
            <div class="campaignstats-digit">90%</div>
        </li>
    </ul>
    <!-- BOTTOM ANALYTICS -->
    <ul class="campaignstats-row analyticsstats-row">
        <li>
            <div class="campaignstats-title">AVG. TIME ON SITE</div>
            <div class="campaignstats-digit">60,551</div>
        </li>
        <li>
            <div class="campaignstats-title">AVG. SCROLL DEPTH</div>
            <div class="campaignstats-digit">37%</div>
        </li>
        <li>
            <div class="campaignstats-title">SCROLL DEPTH TO CONV.</div>
            <div class="campaignstats-digit">1%</div>
        </li>
        <li>
            <div class="campaignstats-title">AD BLOCKER</div>
            <div class="campaignstats-digit">14%</div>
        </li>
    </ul>
    <!-- LINE CHART LEFT -->
    <div class="analytics-linechartleft">
      <!-- BROWSERS -->
      <div class="analytics-linechartheader">BROWSERS</div>
      <div class="analytics-linechartsubhead">TOP BROWSERS USED</div>
      <ul class="analytics-linechartlist">
        <li>
          <div class="analytics-linecharttitle">CHROME</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="85%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">SAFARI</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="27%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">ANDROID</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="14%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">INTERNET EXPLORER</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="5%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">OPERA</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="2%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linechartview">VIEW ALL</div>
        </li>
      </ul>
    </div><!-- END LINE CHART -->
    <!-- LINE CHART RIGHT -->
    <div class="analytics-linechartright">
      <!-- AD COMPATIBILITY -->
      <div class="analytics-linechartheader">AD COMPATIBILITY</div>
      <div class="analytics-linechartsubhead">HTML5 VS FLASH</div>
      <ul class="analytics-linechartlist">
        <li>
          <div class="analytics-linecharttitle">HTML5</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="68%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">SAFARI</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="32%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linechartview">VIEW ALL</div>
        </li>
      </ul>
    </div><!-- END LINE CHART -->
    <!-- LINE CHART LEFT -->
    <div class="analytics-linechartleft">
      <!-- BROWSERS -->
      <div class="analytics-linechartheader">DEVICES</div>
      <div class="analytics-linechartsubhead">TOP DEVICE TYPE</div>
      <ul class="analytics-linechartlist">
        <li>
          <div class="analytics-linecharttitle">DESKTOP (WINDOWS)</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="85%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">DESKTOP (APPLE)</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="27%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">IPHONE</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="14%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">ANDROID</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="5%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">OTHER</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="2%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linechartview">VIEW ALL</div>
        </li>
      </ul>
    </div><!-- END LINE CHART -->
    <!-- LINE CHART RIGHT -->
    <div class="analytics-linechartright">
      <!-- AD COMPATIBILITY -->
      <div class="analytics-linechartheader">SCREEN SIZES</div>
      <div class="analytics-linechartsubhead">TOP SCREEN SIZES</div>
      <ul class="analytics-linechartlist">
        <li>
          <div class="analytics-linecharttitle">320 X 480</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="85%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">1920 X 1080</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="27%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">1280 X 800</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="14%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">360 X 640</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="5%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">720 X 1280</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="2%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linechartview">VIEW ALL</div>
        </li>
      </ul>
    </div><!-- END LINE CHART -->
    <!-- LINE CHART LEFT -->
    <div class="analytics-linechartleft">
      <!-- BROWSERS -->
      <div class="analytics-linechartheader">GEO LOCATION</div>
      <div class="analytics-linechartsubhead">TOP LOCATIONS</div>
      <ul class="analytics-linechartlist">
        <li>
          <div class="analytics-linecharttitle">UNITED STATES</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="85%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">UNITED KINGDOM</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="27%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">GERMANY</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="14%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">RUSSIA</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="5%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">FRANCE</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="2%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linechartview">VIEW ALL</div>
        </li>
      </ul>
    </div><!-- END LINE CHART -->
    <!-- LINE CHART RIGHT -->
    <div class="analytics-linechartright">
      <!-- AD COMPATIBILITY -->
      <div class="analytics-linechartheader">INTERESTS</div>
      <div class="analytics-linechartsubhead">TOP INTEREST'S BASED ON PAGE CONTENT</div>
      <ul class="analytics-linechartlist">
        <li>
          <div class="analytics-linecharttitle">NEWS / MEDIA</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="85%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">SHOPPING</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="27%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">MOVIES</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="14%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">TELEVISION</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="5%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linecharttitle">BOOKS & LITERATURE</div>
          <div class="analytics-linechartnum">%</div>
          <div class="analytics-linechartwrap">
            <div class="analytics-linechartback"></div>
            <div class="analytics-linechartfront" data-width="2%"></div>
          </div>
        </li>
        <li>
          <div class="analytics-linechartview">VIEW ALL</div>
        </li>
      </ul>
    </div><!-- END LINE CHART -->
  </div>
</template>

<script>
  export default {
    name: 'Analytics',

    mounted() {
      $('ul.analytics-linechartlist li').each(function() {
        let analLineFind = $(this).find('.analytics-linechartfront')
        let analLineWidth = $(analLineFind).data('width')
        $(this).find('.analytics-linechartfront').css('width', analLineWidth)
        $(this).find('.analytics-linechartnum').html(analLineWidth)
      })
    }
  }
</script>
