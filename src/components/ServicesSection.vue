<script setup lang="ts">
import {
  ShoppingBag,
  Headphones,
  Plane,
  Hotel,
  Factory,
  Users2,
  CalendarDays,
  UserCheck,
  ClipboardList,
  Dog,
  Leaf,
  Search,
  ShieldAlert,
  UserRoundCheck,
  Camera,
} from '@lucide/vue'

const services = [
  { icon: ShoppingBag, label: 'Retail / Mall Security' },
  { icon: Headphones, label: 'BPO Security' },
  { icon: Plane, label: 'Aviation Security' },
  { icon: Hotel, label: 'Hotel Security' },
  { icon: Factory, label: 'Industrial Security' },
  { icon: Users2, label: 'Guard Force Management' },
  { icon: CalendarDays, label: 'Event Security' },
  { icon: UserCheck, label: 'VIP Protection Services' },
  { icon: ClipboardList, label: 'Security Consultancy' },
  { icon: Dog, label: 'K9 Services' },
  { icon: Leaf, label: 'EOHS Assessment' },
  { icon: Search, label: 'Security Survey' },
  { icon: ShieldAlert, label: 'Vulnerability Assessment' },
  { icon: UserRoundCheck, label: 'VIP Protection' },
  { icon: Camera, label: 'E-Guarding' },
]
</script>

<template>
  <section id="services" class="section services">
    <div class="container">
      <div class="section-header">
        <span class="section-eyebrow">Services Offered</span>
        <h2 class="section-title">Comprehensive coverage, tailored protection</h2>
        <p class="section-lead">
          From manpower deployment to consultancy and e-guarding, our services adapt to the
          unique threat profile of every client.
        </p>
      </div>

      <ul class="svc-grid">
        <li v-for="s in services" :key="s.label" class="svc-card">
          <span class="svc-icon">
            <component :is="s.icon" :size="20" />
          </span>
          <span class="svc-label">{{ s.label }}</span>
        </li>
      </ul>
    </div>
  </section>
</template>

<style scoped>
.services {
  background: var(--color-bg);
}

.svc-grid {
  list-style: none;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 0.85rem;
}

.svc-card {
  display: flex;
  align-items: center;
  gap: 0.85rem;
  background: #fff;
  padding: 1rem 1.1rem;
  border-radius: var(--radius-md);
  border: 1px solid var(--color-border);
  transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
}

.svc-card:hover {
  transform: translateY(-2px);
  border-color: var(--color-accent);
  box-shadow: 0 8px 20px var(--color-shadow);
}

.svc-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 38px;
  height: 38px;
  border-radius: 10px;
  background: rgba(74, 95, 128, 0.1);
  color: var(--color-primary);
  flex-shrink: 0;
}

.svc-label {
  font-size: 0.92rem;
  font-weight: 600;
  color: var(--color-primary-dark);
}
</style>
