<template>
  <div>
    <input name="tagmanage-tabbed" id="tagmanage-tabbed3" type="radio">
    <section>
      <h1>
        <label for="tagmanage-tabbed3">GLOBAL OPTIONS</label>
      </h1>
      <div>
        <!-- CREATE GLOBAL OPTIONS -->
        <div class="tagmanage-tagcreationwrappers" style="margin-top:58px;">
          <div class="tagcreate-formwrapper">
            <div class="tagcreate-fullheadertitle">GLOBAL PRESETS</div>
            <div class="tagcreate-formbg">
              <div class="tagcreate-quarterinnerwrap" style="margin-left:0;">
                <div class="tagcreate-fullinnertitle">TIMEOUT LIMIT</div>
                <input class="tagcreate-longinput" v-model="timeoutLimit" placeholder="0">
              </div>
              <div class="tagcreate-quarterinnerwrap">
                <div class="tagcreate-fullinnertitle">WRAPPER LIMIT</div>
                <input class="tagcreate-longinput" v-model="wrapperLimit" placeholder="0">
              </div>
              <div class="tagcreate-quarterinnerwrap">
                <div class="tagcreate-fullinnertitle">DELAY TIME</div>
                <input class="tagcreate-longinput" v-model="delayTime" placeholder="0">
              </div>
            </div><!-- END .tagcreate-formbg -->
          </div><!-- END .tagcreate-formwrapper -->

          <!-- SAVE GLOBAL PRESETS -->
          <div class="tagcreate-savetagwrap" style="margin-top:30px;">
            <div class="tagcreate-savetagbutton" style="width:100%;" @click="savePresets()">SAVE GLOBAL PRESETS</div>
          </div>
          <!-- END SAVE GLOBAL PRESETS -->

        </div>
        <!-- END GLOBAL OPTIONS -->
      </div>
    </section>
  </div>
</template>

<script>
  import toCamelCase from 'to-camel-case'

  export default {
    name: 'GlobalOptions',

    data() {
      return {
        wrapperLimit: 0,
        timeoutLimit: 0,
        delayTime: 0
      }
    },

    methods: {
      savePresets() {
        this.$store.dispatch('admin/updateGlobalOptions', {
          wrapperLimit: this.wrapperLimit,
          timeoutLimit: this.timeoutLimit,
          delayTime: this.delayTime
        })
        window.alert('Saved')
      }
    },

    computed: {
      globalOptions() {
        return this.$store.state.admin.globalOptions
      }
    },

    watch: {
      globalOptions: function(newValue) {
        let vm = this
        newValue.map(option => {
          vm.$set(vm, toCamelCase(option.option), option.value)
        })
      }
    }
  }
</script>
