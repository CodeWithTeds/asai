<script setup lang="ts">
import { Eye, Target } from '@lucide/vue'
import { useScrollReveal } from '@/composables/useScrollReveal'

const { containerRef } = useScrollReveal()

const visionPoints = [
  'A world-class Security Service Company',
  'An ISO-Certified Security Management Company',
  'To initiate the professionalization of enterprise security practice in the country — creating an environment of professional acceptance of holistic security concepts.',
  'To develop and maintain a secure environment where maximum productivity is assured and people may enjoy the fruits of their labor free of anxiety and fear.',
]
</script>

<template>
  <section id="vision" class="section vision-mission" ref="containerRef">
    <div class="container vm-grid">
      <article class="vm-card reveal-left reveal">
        <div class="vm-icon icon-pulse">
          <Eye :size="22" />
        </div>
        <h2 class="vm-title">Vision Statement</h2>
        <ul class="vm-list">
          <li v-for="point in visionPoints" :key="point">{{ point }}</li>
        </ul>
      </article>

      <article class="vm-card vm-card--accent reveal-right reveal">
        <div class="vm-icon vm-icon--accent icon-pulse">
          <Target :size="22" />
        </div>
        <h2 class="vm-title">Mission Statement</h2>
        <p class="vm-text">
          To upgrade the level of security service by applying an integrated system approach for
          greater specialization — adapting to current and evolving security and safety
          challenges, making us a trustworthy partner for business development of our current and
          future clients.
        </p>
      </article>
    </div>
  </section>
</template>

<style scoped>
.vision-mission {
  background: var(--color-bg);
}

.vm-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
}

.vm-card {
  background: #fff;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-lg);
  padding: 2.5rem;
  position: relative;
  overflow: hidden;
  transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
}

.vm-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 16px 40px rgba(74, 95, 128, 0.14);
}

.vm-card--accent {
  background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
  color: #fff;
  border-color: transparent;
}

.vm-card--accent::before {
  content: '';
  position: absolute;
  top: -60px;
  right: -60px;
  width: 200px;
  height: 200px;
  background: radial-gradient(circle, rgba(221, 179, 96, 0.35), transparent 70%);
}

.vm-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 48px;
  height: 48px;
  border-radius: 12px;
  background: rgba(74, 95, 128, 0.1);
  color: var(--color-primary);
  margin-bottom: 1.25rem;
}

.vm-icon--accent {
  background: rgba(221, 179, 96, 0.2);
  color: var(--color-accent);
}

.vm-title {
  font-size: 1.6rem;
  margin-bottom: 1.25rem;
}

.vm-card--accent .vm-title {
  color: #fff;
}

.vm-list {
  list-style: none;
  display: grid;
  gap: 0.75rem;
}

.vm-list li {
  position: relative;
  padding-left: 1.5rem;
  color: var(--color-text-muted);
  font-size: 0.98rem;
}

.vm-list li::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0.65em;
  width: 8px;
  height: 8px;
  border-radius: 2px;
  background: var(--color-accent);
}

.vm-text {
  color: rgba(255, 255, 255, 0.9);
  font-size: 1.05rem;
  line-height: 1.7;
}

@media (min-width: 900px) {
  .vm-grid {
    grid-template-columns: 1.1fr 1fr;
    gap: 2rem;
  }
}
</style>
