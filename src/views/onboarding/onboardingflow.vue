<template>
  <div class="onboarding">
    <div class="progress-dots" v-if="currentStep > 0 && currentStep < totalSteps - 1">
      <span
        v-for="i in totalSteps - 2"
        :key="i"
        class="dot"
        :class="{ active: i <= currentStep, current: i === currentStep }"
      ></span>
    </div>

    <button
      class="back-btn"
      v-if="currentStep > 0 && currentStep < totalSteps - 1"
      @click="prevStep"
    >
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
        <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2.5"
              stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>

    <transition :name="transitionName" mode="out-in">
      <component
        :is="steps[currentStep]"
        :key="currentStep"
        :form-data="formData"
        @next="handleNext"
        @prev="prevStep"
      />
    </transition>
  </div>
</template>

<script>
import { markRaw } from 'vue'
import { useApi } from '@/composables/useApi.js'
import Step01Welcome from './steps/Step01Welcome.vue'
import Step02Name from './steps/Step02Name.vue'
import Step03Email from './steps/Step03Email.vue'
import Step04Password from './steps/Step04Password.vue'
import Step05Notifications from './steps/Step05Notifications.vue'
import Step06Done from './steps/Step06Done.vue'

const steps = [
  markRaw(Step01Welcome),
  markRaw(Step02Name),
  markRaw(Step03Email),
  markRaw(Step04Password),
  markRaw(Step05Notifications),
  markRaw(Step06Done)
]

export default {
  name: 'OnboardingFlow',
  setup() {
    return { api: useApi() }
  },
  data() {
    return {
      currentStep: 0,
      transitionName: 'slide-left',
      formData: {
        name: '',
        email: '',
        password: '',
        notifications: false
      },
      registrationError: '',
    }
  },
  computed: {
    steps() { return steps },
    totalSteps() { return steps.length }
  },
  methods: {
    handleNext(payload) {
      if (payload) Object.assign(this.formData, payload)
      this.transitionName = 'slide-left'

      
      if (this.currentStep === steps.length - 2) {
        this.submit()
      }

      if (this.currentStep < steps.length - 1) {
        this.currentStep++
      }
    },
    prevStep() {
      if (this.currentStep > 0) {
        this.transitionName = 'slide-right'
        this.$nextTick(() => { this.currentStep-- })
      }
    },
    async submit() {
      this.registrationError = ''
      try {
        const result = await this.api.register({
          name:          this.formData.name,
          email:         this.formData.email,
          password:      this.formData.password,
          notifications: this.formData.notifications,
        })
        
        localStorage.setItem('moveo_name',    this.formData.name)
        localStorage.setItem('moveo_user_id', result.user_id || '')
        localStorage.setItem('user_authenticated', 'true')
      } catch (e) {
        
        localStorage.setItem('moveo_name', this.formData.name)
        localStorage.setItem('user_authenticated', 'true')
        this.registrationError = e.message || ''
        console.warn('Backend registro falló (modo offline):', e.message)
      }
    }
  }
}
</script>

<style scoped>

.onboarding {
  flex: 1; display: flex; flex-direction: column;
  background: linear-gradient(160deg, #e8f4ff 0%, #f0f7ff 50%, #fff 100%);
  position: relative; overflow: hidden; min-height: 100dvh;
}
.progress-dots { position: absolute; top: 26px; left: 50%; transform: translateX(-50%); display: flex; gap: 7px; z-index: 10; }
.dot { width: 8px; height: 8px; border-radius: 50%; background: #c8ddf7; transition: all 0.3s; }
.dot.active { background: #4a90e2; }
.dot.current { width: 22px; border-radius: 4px; background: #4a90e2; }
.back-btn { position: absolute; top: 18px; left: 16px; z-index: 10; width: 40px; height: 40px; border-radius: 50%; border: none; background: white; color: #4a90e2; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }

.slide-left-enter-active, .slide-left-leave-active, .slide-right-enter-active, .slide-right-leave-active { transition: all 0.32s cubic-bezier(.4,0,.2,1); }
.slide-left-enter-from { opacity: 0; transform: translateX(56px); }
.slide-left-leave-to { opacity: 0; transform: translateX(-56px); }
.slide-right-enter-from { opacity: 0; transform: translateX(-56px); }
.slide-right-leave-to { opacity: 0; transform: translateX(56px); }
</style>

