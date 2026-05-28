<script setup lang="ts">
import { Building2, Handshake, ScrollText } from '@lucide/vue'
import { useScrollReveal } from '@/composables/useScrollReveal'

const { containerRef } = useScrollReveal()

const milestones = [
  {
    icon: ScrollText,
    title: 'Established in 2014',
    text: 'Founded by Senior Police Officer Atty. Urvi B. Pascual to deliver security and safety services.',
  },
  {
    icon: Building2,
    title: 'SEC Registered',
    text: 'Registered with the Securities and Exchange Commission under Number CS201809381.',
  },
  {
    icon: Handshake,
    title: 'AsiaLink Affiliate',
    text: 'Merged with the AsiaLink Group of Companies in 2018 to expand financial and network capabilities.',
  },
]
</script>

<template>
  <section id="about" class="section about" ref="containerRef">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-eyebrow">About ASAI</span>
        <h2 class="section-title">A trusted security partner since 2014</h2>
        <p class="section-lead">
          Formerly UBP Security Agency, ASAI is a service-oriented corporation with strong
          financial capabilities, technically and financially ready to undertake contractual
          obligations across the government and private sectors.
        </p>
      </div>

      <div class="milestones reveal-stagger">
        <article v-for="m in milestones" :key="m.title" class="milestone reveal hover-lift">
          <div class="milestone-icon icon-pulse">
            <component :is="m.icon" :size="22" />
          </div>
          <h3 class="milestone-title">{{ m.title }}</h3>
          <p class="milestone-text">{{ m.text }}</p>
        </article>
      </div>

      <p class="motto reveal">
        "TRANSFORMING SECURITY FOR A BETTER BUSINESS" — a guarantee that all clients are assured
        of protection and security with dedication and honesty.
      </p>
    </div>
  </section>
</template>

<style scoped>
.about {
  background: #fff;
}

.milestones {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 1.5rem;
  margin-bottom: 3rem;
}

.milestone {
  background: var(--color-bg);
  padding: 1.75rem;
  border-radius: var(--radius-md);
  border: 1px solid var(--color-border);
}

.milestone-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 44px;
  height: 44px;
  border-radius: 10px;
  background: rgba(221, 179, 96, 0.15);
  color: var(--color-accent);
  margin-bottom: 1rem;
}

.milestone-title {
  font-family: var(--font-sans);
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--color-primary-dark);
  margin-bottom: 0.5rem;
}

.milestone-text {
  font-size: 0.95rem;
  color: var(--color-text-muted);
}

.motto {
  text-align: center;
  font-family: var(--font-serif);
  font-style: italic;
  font-size: 1.15rem;
  color: var(--color-primary);
  max-width: 760px;
  margin: 0 auto;
  padding-top: 1rem;
  border-top: 1px solid var(--color-border);
}
</style>
