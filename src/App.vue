<script setup lang="ts">
import AppHeader from './components/AppHeader.vue'
import HeroSection from './components/HeroSection.vue'
import AboutSection from './components/AboutSection.vue'
import VisionMissionSection from './components/VisionMissionSection.vue'
import CapabilitiesSection from './components/CapabilitiesSection.vue'
import ServicesSection from './components/ServicesSection.vue'
import QualitySection from './components/QualitySection.vue'
import AppFooter from './components/AppFooter.vue'
</script>

<template>
  <AppHeader />
  <main>
    <HeroSection />
    <AboutSection />
    <VisionMissionSection />
    <CapabilitiesSection />
    <ServicesSection />
    <QualitySection />
  </main>
  <AppFooter />
</template>
