<template>
    <div>
        <div class="leftsidebar">
            <div class="logoarea">
                <div class="videologo"></div>
            </div>

            <ul class="navlist">
                <a v-link="'/dashboard'">
                    <li v-link-active>DASHBOARD</li>
                </a>
                <a v-link="'/analytics'">
                    <li class="">ANALYTICS</li>
                </a>

                <a v-link="'/campaigns'">
                    <li class="">CAMPAIGNS</li>
                </a>

                <a v-link="'/create-campaigns'">
                    <li class="">CREATE CAMPAIGN</li>
                </a>

                <a v-link="'/web-config'">
                    <li class="">WEBSITE CONFIG</li>
                </a>

                <a v-link="'/support'">
                    <li class="">SUPPORT</li>
                </a>
            </ul>
        </div>
    </div>
</template>
