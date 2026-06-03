<script setup lang="ts">
import '../../css/welcome/main.css';
import AboutSection from '@/components/Welcome/AboutSection.vue';
import AppFooter from '@/components/Welcome/AppFooter.vue';
import AppHeader from '@/components/Welcome/AppHeader.vue';
import CapabilitiesSection from '@/components/Welcome/CapabilitiesSection.vue';
import HeroSection from '@/components/Welcome/HeroSection.vue';
import JobPostingsSection from '@/components/Welcome/JobPostingsSection.vue';
import QualitySection from '@/components/Welcome/QualitySection.vue';
import ServicesSection from '@/components/Welcome/ServicesSection.vue';
import VisionMissionSection from '@/components/Welcome/VisionMissionSection.vue';
</script>

<template>
    <AppHeader />
    <main>
        <HeroSection />
        <AboutSection />
        <VisionMissionSection />
        <CapabilitiesSection />
        <ServicesSection />
        <JobPostingsSection />
        <QualitySection />
    </main>
    <AppFooter />
</template>
