<template>
    <div class="selectadtype-overlay" v-cloak>
        <div id="adcreation-form">
            <div class="steps clearfix">
                <ul>
                    <li v-for="(index, tab) in tabs" :class="{'current': step == tab.name, 'disabled': tab.disabled}" @click="toStep(index + 1)">
                        <span class="number">@{{ index + 1 }}.</span> @{{ tab.title }}
                    </li>
                </ul>
            </div>

            <!-- start select ad type -->
            <div class="adcreation-section" v-show="step == 'type'">
                <div class="selectadtype-title">Select your ad type to proceed:</div>
                <div class="selectadtype-wrapper">
                    <ul class="selectadtype-adtypes">
                        <li v-for="(type, info) in campaign_types" :class="{'disabled': !info.available}" @click="pickAdType(type)">
                            <img :src="'/assets/images/adtype-'+type+'.png'">
                            <div class="selectadtype-adtypetitle">@{{ info.title }}</div>
                            <div class="selectadtype-adtypeselect">select this ad</div>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- end select ad type -->

            <!-- start create ad name -->
            <div class="adcreation-section" v-show="step == 'name'">
                <div class="selectadtype-title">
                    @{{ selectedCampaign.has_name ? 'Create a Reference Name for your Ad:' : 'Ad your youtube link' }}

                    <div class="message error" v-if="error">
                        @{{ error }}
                    </div>
                </div>
                <div class="selectadtype-wrapper">
                    <div class="createcampaign-fulltoparea">
                        <div class="campaign-creationwrap createcampaign-middlecreatewrap">
                            <form name="campaignForm" @submit.prevent.default="checkPreview()">
                                <div class="campaign-creationyoutube" v-if="!selectedCampaign.has_name">
                                    <label for="campaign_name">Youtube</label>
                                    <input id="campaign_name" type="text" placeholder="https://www.youtube.com/watch?v=AbcDe1FG234" required v-model="campaign.video">
                                </div>

                                <div class="campaign-creationyoutube" v-if="selectedCampaign.has_name">
                                    <label for="campaign_name">NAME</label>
                                    <div class="campaignform-error hidden">Already same title exists.</div>
                                    <input id="campaign_name" type="text" placeholder="Reference name.." required v-model="campaign.name">
                                </div>

                                <div class="campaign-creationvidsize">
                                    <label for="video_size">VIDEO SIZE</label>

                                    <select id="video_size" class="yt-uix-form-input-select-element" required v-model="campaign.size">
                                        <option v-for="(key, value) in sizes" :value="key" :selected="campaign.size==key">@{{value | capitalize }}</optgroup>
                                    </select>
                                </div>

                                <button>PROCEED TO PREVIEW</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end create ad name -->

            <div class="adcreation-section" v-show="step == 'preview'">
                <div class="selectadtype-title">
                    <div v-if="!loading">Your video preview</div>
                    <div v-else>Please wait..</div>
                </div>

                <div class="selectadtype-wrapper">
                    <div class="createcampaign-fulltoparea">
                        <div class="campaign-creationwrap createcampaign-middlecreatewrap preview" v-el:preview-container></div>
                    </div>
                </div>

                <div style="clear: both;color: white;padding-top: 20px;text-align: center;" v-show="!loading">
                    <button @click="save()">Save</button>
                </div>
            </div>

            <div class="adcreation-section" v-show="step == 'code'">
                <div class="selectadtype-title">
                    <div class="message success" v-if="!loading">
                        Campaign "@{{ savedCampaign.name }}" is now saved
                    </div>
                    <div v-if="loading">Please wait..</div>
                </div>

                <div style="margin: 0 auto;" v-if="!loading">
                    <div class="createcampaign-fulltoparea">
                        <div class="campaign-creationwrap createcampaign-middlecreatewrap" style="background: none;">
                            <label for="embed_js" class="white" style="font-size: 14px">Copy and paste the code below in your website:</label>
                            <textarea id="embed_js" style="width: 100%;height: 100%;resize: none;" v-el:embed-js-code @click="selectEmbedText()"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {

    }
</script>

<style scoped>
    .selectadtype-overlay li {
        cursor: pointer;
    }

    .selectadtype-overlay li.disabled {
        pointer-events: none;
        color: #4A5263 !important;
    }

    .selectadtype-overlay li.current {
        color: #FFFFFF !important;
    }

    .videosize {
        width: 90px !important;
    }

    .selectadtype-overlay button {
        background: #8883B9;
        text-align: center;
        display: inline-block;
        width: 200px;
        height: 46px;
        line-height: 46px;
        font-size: 12px;
        color: #FFFFFF;
        border: none;
        cursor: pointer;
    }

    .message {
        max-width: 650px;
        padding: 10px 0;
        margin: 0 auto;
    }

    .message.error {
        background: red;
    }

    .message.success {
        background: #4596CB;
    }

    label.white {
        float: left;
        width: 100%;
        font-size: 10px;
        color: white;
        font-weight: 600;
        margin-bottom: 9px;
    }
</style>
