<template>
    <div>
        <div class="leftsidebar">
            <div class="logoarea">
                <div class="videologo"></div>
            </div>

            <ul class="navlist">
                <router-link :to="{ name: 'dashboard'}" tag="li">
                    <a>
                        <i class="fa fa-dashboard"></i> <span>DASHBOARD</span>
                    </a>
                </router-link>

                <router-link :to="{ name: 'campaigns.listing'}" tag="li">
                    <a>
                        <i class="fa fa-newspaper-o"></i> <span>CAMPAIGNS</span>
                    </a>
                </router-link>

                <router-link :to="{ name: 'campaigns.create'}" tag="li">
                    <a>
                        <i class="fa fa-plus"></i> <span>CREATE CAMPAIGN</span>
                    </a>
                </router-link>

                <router-link :to="{ name: 'analytics'}" tag="li">
                    <a>
                        <i class="fa fa-bar-chart"></i> <span>ANALYTICS</span>
                    </a>
                </router-link>

                <router-link :to="{ name: 'webconfig'}" tag="li">
                    <a>
                        <i class="fa fa-globe"></i> <span>WEBSITES</span>
                    </a>
                </router-link>

                <router-link :to="{ name: 'support'}" tag="li">
                    <a>
                        <i class="fa fa-flag"></i> <span>SUPPORT</span>
                    </a>
                </router-link>
            </ul>
        </div>
    </div>
</template>
