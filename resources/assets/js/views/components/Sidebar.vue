<template>
    <div>
        <div class="leftsidebar">
            <div class="logoarea">
                <div class="videologo"></div>
            </div>

            <ul class="navlist">
                <a v-link="{name:'dashboard'}">
                    <li v-link-active>DASHBOARD</li>
                </a>
                <a v-link="{name:'analytics'}">
                    <li class="">ANALYTICS</li>
                </a>

                <a v-link="{name:'campaigns.listing'}">
                    <li class="">CAMPAIGNS</li>
                </a>

                <a v-link="{name:'campaigns.create'}">
                    <li class="">CREATE CAMPAIGN</li>
                </a>

                <a v-link="{name:'webconfig'}">
                    <li class="">WEBSITE CONFIG</li>
                </a>

                <a v-link="{name:'support'}">
                    <li class="">SUPPORT</li>
                </a>
            </ul>
        </div>
    </div>
</template>
