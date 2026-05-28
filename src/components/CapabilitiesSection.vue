<script setup lang="ts">
import {
  FileCheck2,
  Crown,
  Layers,
  GraduationCap,
  BadgeCheck,
  Network,
  Truck,
  Landmark,
} from '@lucide/vue'
import { useScrollReveal } from '@/composables/useScrollReveal'

const { containerRef } = useScrollReveal()

const capabilities = [
  {
    icon: FileCheck2,
    title: 'Audited Financial Transparency',
    text: 'Strong financial statements regularly audited by Reyes Tacandong & Co., ensuring transparency, compliance, and credibility with clients, partners, and investors.',
  },
  {
    icon: Crown,
    title: 'Experienced Leadership',
    text: 'Led by seasoned professionals with extensive experience in security operations, law enforcement coordination, and corporate management.',
  },
  {
    icon: Layers,
    title: 'Scalable Security Operations',
    text: 'Operational capability to deploy security personnel efficiently across multiple sites nationwide — supporting both small and large-scale requirements.',
  },
  {
    icon: GraduationCap,
    title: 'Highly Trained Personnel',
    text: 'Officers undergo rigorous recruitment screening, training, and continuous professional development to meet the highest standards of operational readiness.',
  },
  {
    icon: BadgeCheck,
    title: 'PNP-SOSIA Licensed Training',
    text: 'A dedicated Training Director licensed by PNP-SOSIA ensures all personnel programs comply with national regulatory standards and industry requirements.',
  },
  {
    icon: Network,
    title: 'Integrated Security Solutions',
    text: 'Beyond manpower deployment: risk assessment, security planning, incident management, and coordination with local authorities.',
  },
  {
    icon: Truck,
    title: 'Logistically Prepared',
    text: 'Adequate logistical resources including mobility, communication systems, and operational support for rapid deployment and emergency response.',
  },
  {
    icon: Landmark,
    title: 'Government & Law Enforcement',
    text: 'Established coordination with PNP, NBI, MMDA, and other national authorities for efficient incident handling and security operations.',
  },
]
</script>

<template>
  <section id="capabilities" class="section capabilities" ref="containerRef">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-eyebrow">Agency Capability</span>
        <h2 class="section-title">Built on capability, governed by integrity</h2>
        <p class="section-lead">
          Eight pillars that define how we operate, recruit, train, and protect.
        </p>
      </div>

      <div class="cap-grid reveal-stagger">
        <article v-for="(c, i) in capabilities" :key="c.title" class="cap-card reveal hover-lift">
          <span class="cap-number">{{ String(i + 1).padStart(2, '0') }}</span>
          <div class="cap-icon icon-pulse">
            <component :is="c.icon" :size="22" />
          </div>
          <h3 class="cap-title">{{ c.title }}</h3>
          <p class="cap-text">{{ c.text }}</p>
        </article>
      </div>
    </div>
  </section>
</template>

<style scoped>
.capabilities {
  background: #fff;
}

.cap-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 1.25rem;
}

.cap-card {
  position: relative;
  background: var(--color-bg);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-md);
  padding: 1.75rem;
}

.cap-number {
  position: absolute;
  top: 1rem;
  right: 1.25rem;
  font-family: var(--font-display);
  font-size: 1.5rem;
  font-weight: 700;
  color: rgba(74, 95, 128, 0.15);
}

.cap-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 44px;
  height: 44px;
  border-radius: 10px;
  background: var(--color-primary);
  color: #fff;
  margin-bottom: 1rem;
}

.cap-title {
  font-family: var(--font-sans);
  font-size: 1.05rem;
  font-weight: 700;
  color: var(--color-primary-dark);
  margin-bottom: 0.5rem;
}

.cap-text {
  font-size: 0.92rem;
  color: var(--color-text-muted);
  line-height: 1.6;
}
</style>
