<template>
    <div>
        <div class="leftsidebar">
            <div class="logoarea">
                <div class="videologo"></div>
            </div>

            <ul class="navlist">
                <a v-link="dashboard">
                    <li class="">DASHBOARD</li>
                </a>

                <a v-link="analytics">
                    <li class="">ANALYTICS</li>
                </a>

                <a v-link="campaigns">
                    <li class="">CAMPAIGNS</li>
                </a>

                <a v-link="create-campaigns">
                    <li class="">CREATE CAMPAIGN</li>
                </a>

                <a v-link="web-config">
                    <li class="">WEBSITE CONFIG</li>
                </a>

                <a v-link="support">
                    <li class="">SUPPORT</li>
                </a>
            </ul>
        </div>

        <!-- RIGHT SIDE -->
        <div class="rightside">
            <div class="rightside-nav">
                <div class="rightside-navlefttitle">

                </div>

                <!-- ACCOUNT DETAILS -->
                <div id="accountdetails" class="rightside-navdropdown">ACCOUNT DETAILS <span></span></div>
                <ul id="accountdetails-navdroparea" class="rightside-navdroparea" style="display:none;">
                    <a href="/app/profile">
                        <li>EDIT ACCOUNT</li>
                    </a>
                    <a href="/app/logout">
                        <li>LOGOUT</li>
                    </a>
                </ul>

                <!-- AVAILABLE WEBSITES -->
                <div id="availablesites" class="rightside-navdropdown">WEBSITES <span></span></div>
                <ul id="availablesites-navdroparea" class="rightside-navdroparea" style="display:none;">
                    <a href="##">
                        <li>SITE.COM</li>
                    </a>
                    <a href="##">
                        <li>ABC.COM</li>
                    </a>
                </ul>
            </div>

            <router-view></router-view>
        </div>
    </div>
</template>
