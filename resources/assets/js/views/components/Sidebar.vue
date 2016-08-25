<template>
    <div>
        <div class="leftsidebar">
            <div class="logoarea">
                <div class="videologo"></div>
            </div>

            <ul class="navlist">
                <li v-link="{name:'dashboard'}">
                    <a>
                        <i class="fa fa-dashboard"></i> <span>DASHBOARD</span>
                    </a>
                </li>

                <li v-link="{name:'campaigns.listing'}">
                    <a>
                        <i class="fa fa-newspaper-o"></i> <span>CAMPAIGNS</span>
                    </a>
                </li>

                <li v-link="{name:'campaigns.create'}">
                    <a>
                        <i class="fa fa-plus"></i> <span>CREATE CAMPAIGN</span>
                    </a>
                </li>

                <li v-link="{name:'analytics'}">
                    <a>
                        <i class="fa fa-bar-chart"></i> <span>ANALYTICS</span>
                    </a>
                </li>

                <li v-link="{name:'webconfig'}">
                    <a>
                        <i class="fa fa-globe"></i> <span>WEBSITES</span>
                    </a>
                </li>

                <li v-link="{name:'support'}">
                    <a>
                        <i class="fa fa-flag"></i> <span>SUPPORT</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>
