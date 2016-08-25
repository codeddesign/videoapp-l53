<template>
    <div>
        <div class="leftsidebar">
            <div class="logoarea">
                <div class="videologo"></div>
            </div>

            <ul class="navlist">
                <li v-link="{name:'dashboard'}">
                    <a>
                        <i class="fa fa-dashboard"></i> DASHBOARD
                    </a>
                </li>

                <li v-link="{name:'campaigns.listing'}">
                    <a>
                        <i class="fa fa-newspaper-o"></i> CAMPAIGNS
                    </a>
                </li>

                <li v-link="{name:'campaigns.create'}">
                    <a>
                        <i class="fa fa-plus"></i> CREATE CAMPAIGN
                    </a>
                </li>

                <li v-link="{name:'analytics'}">
                    <a>
                        <i class="fa fa-bar-chart"></i> ANALYTICS
                    </a>
                </li>

                <li v-link="{name:'webconfig'}">
                    <a>
                        <i class="fa fa-globe"></i> WEBSITES
                    </a>
                </li>

                <li v-link="{name:'support'}">
                    <a>
                        <i class="fa fa-flag"></i> SUPPORT
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>
