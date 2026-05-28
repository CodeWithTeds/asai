<script setup lang="ts">
import { onMounted, onUnmounted, ref, computed } from 'vue'
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
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

gsap.registerPlugin(ScrollTrigger)

const capabilities = [
  {
    icon: FileCheck2,
    title: 'Audited Financial Transparency',
    text: 'Strong financial statements regularly audited by Reyes Tacandong & Co., ensuring transparency, compliance, and credibility with clients, partners, and investors.',
    align: 'left',
  },
  {
    icon: Crown,
    title: 'Experienced Leadership',
    text: 'Led by seasoned professionals with extensive experience in security operations, law enforcement coordination, and corporate management.',
    align: 'center',
  },
  {
    icon: Layers,
    title: 'Scalable Security Operations',
    text: 'Operational capability to deploy security personnel efficiently across multiple sites nationwide — supporting both small and large-scale requirements.',
    align: 'right',
  },
  {
    icon: GraduationCap,
    title: 'Highly Trained Personnel',
    text: 'Officers undergo rigorous recruitment screening, training, and continuous professional development to meet the highest standards of operational readiness.',
    align: 'left',
  },
  {
    icon: BadgeCheck,
    title: 'PNP-SOSIA Licensed Training',
    text: 'A dedicated Training Director licensed by PNP-SOSIA ensures all personnel programs comply with national regulatory standards and industry requirements.',
    align: 'center',
  },
  {
    icon: Network,
    title: 'Integrated Security Solutions',
    text: 'Beyond manpower deployment: risk assessment, security planning, incident management, and coordination with local authorities.',
    align: 'right',
  },
  {
    icon: Truck,
    title: 'Logistically Prepared',
    text: 'Adequate logistical resources including mobility, communication systems, and operational support for rapid deployment and emergency response.',
    align: 'left',
  },
  {
    icon: Landmark,
    title: 'Government & Law Enforcement',
    text: 'Established coordination with PNP, NBI, MMDA, and other national authorities for efficient incident handling and security operations.',
    align: 'center',
  },
]

const sectionRef = ref<HTMLElement | null>(null)
const activeIndex = ref(0)
let ctx: gsap.Context | undefined

const activeCap = computed(() => capabilities[activeIndex.value] as (typeof capabilities)[number])

onMounted(() => {
  const el = sectionRef.value
  if (!el) return

  const totalItems = capabilities.length
  const scrollPerItem = 60

  ctx = gsap.context(() => {
    ScrollTrigger.create({
      trigger: el,
      start: 'top top',
      end: `+=${totalItems * scrollPerItem}%`,
      pin: true,
      scrub: 0.3,
      onUpdate: (self) => {
        const progress = self.progress
        const newIndex = Math.min(
          Math.floor(progress * totalItems),
          totalItems - 1
        )
        if (newIndex !== activeIndex.value) {
          activeIndex.value = newIndex
        }
      },
    })
  }, el)
})

onUnmounted(() => {
  ctx?.revert()
})
</script>

<template>
  <section id="capabilities" class="capabilities-section" ref="sectionRef">
    <div class="cap-viewport">
      <div class="cap-header" :class="{ 'cap-header--visible': activeIndex >= 0 }">
        <span class="section-eyebrow">Agency Capability</span>
        <h2 class="section-title">Built on capability, governed by integrity</h2>
        <p class="cap-subtitle">Eight pillars that define how we operate, recruit, train, and protect.</p>
      </div>

      <div class="cap-display" :class="`cap-display--${activeCap.align}`" :key="activeIndex">
        <div class="cap-left">
          <div class="cap-icon-wrap">
            <component :is="activeCap.icon" :size="36" />
          </div>
          <span class="cap-number">{{ String(activeIndex + 1).padStart(2, '0') }}</span>
          <h3 class="cap-title">{{ activeCap.title }}</h3>
        </div>

        <div class="cap-right">
          <p class="cap-text">{{ activeCap.text }}</p>
        </div>
      </div>

      <div class="cap-dots">
        <span
          v-for="(_c, i) in capabilities"
          :key="i"
          class="cap-dot"
          :class="{ active: i === activeIndex, visited: i < activeIndex }"
        ></span>
      </div>
    </div>
  </section>
</template>

<style scoped>
.capabilities-section {
  width: 100%;
  height: 100vh;
  background: #fff;
  overflow: hidden;
}

.cap-viewport {
  width: 100%;
  height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 1.5rem;
  max-width: 1400px;
  margin: 0 auto;
  position: relative;
}

.cap-header {
  text-align: center;
  margin-bottom: clamp(1.5rem, 4vh, 3.5rem);
  opacity: 0;
  transform: translateY(30px) scale(0.95);
  transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

.cap-header--visible {
  opacity: 1;
  transform: translateY(0) scale(1);
}

.cap-header .section-title {
  font-size: clamp(1.5rem, 3.5vw, 2.75rem);
}

.cap-subtitle {
  color: var(--color-text-muted);
  font-size: clamp(0.9rem, 1.2vw, 1.1rem);
  margin-top: 0.5rem;
}

.cap-display {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
  width: 100%;
  max-width: 1100px;
  align-items: center;
}

.cap-display--left {
  animation: zoomFromLeft 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.cap-display--center {
  animation: zoomFromCenter 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.cap-display--right {
  animation: zoomFromRight 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes zoomFromLeft {
  0% { opacity: 0; transform: scale(0.85) translateX(-40px); }
  100% { opacity: 1; transform: scale(1) translateX(0); }
}

@keyframes zoomFromCenter {
  0% { opacity: 0; transform: scale(0.85); }
  100% { opacity: 1; transform: scale(1); }
}

@keyframes zoomFromRight {
  0% { opacity: 0; transform: scale(0.85) translateX(40px); }
  100% { opacity: 1; transform: scale(1) translateX(0); }
}

.cap-left {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.cap-icon-wrap {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: clamp(52px, 6vw, 80px);
  height: clamp(52px, 6vw, 80px);
  border-radius: clamp(12px, 1.5vw, 20px);
  background: var(--color-primary);
  color: #fff;
  transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.cap-display--left .cap-icon-wrap,
.cap-display--center .cap-icon-wrap,
.cap-display--right .cap-icon-wrap {
  transform: scale(1.05);
}

.cap-number {
  font-family: var(--font-display);
  font-size: clamp(2rem, 4vw, 4rem);
  font-weight: 800;
  color: rgba(74, 95, 128, 0.1);
  line-height: 1;
}

.cap-title {
  font-family: var(--font-sans);
  font-size: clamp(1.25rem, 2.5vw, 2.5rem);
  font-weight: 800;
  color: var(--color-primary-dark);
  letter-spacing: -0.02em;
}

.cap-right {
  padding: 1.25rem;
  border-left: 3px solid var(--color-accent);
  min-height: 80px;
  display: flex;
  align-items: center;
}

.cap-text {
  font-size: clamp(0.95rem, 1.3vw, 1.25rem);
  line-height: 1.8;
  color: var(--color-text-muted);
}

.cap-dots {
  position: absolute;
  bottom: clamp(1rem, 3vh, 2.5rem);
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 0.5rem;
}

.cap-dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: var(--color-border);
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.cap-dot.visited {
  background: var(--color-primary-soft);
}

.cap-dot.active {
  background: var(--color-accent);
  transform: scale(1.4);
}

@media (min-width: 768px) {
  .cap-viewport {
    padding: 2rem 2.5rem;
  }

  .cap-display {
    grid-template-columns: 1fr 1.4fr;
    gap: 3rem;
  }

  .cap-right {
    padding: 1.5rem 2rem;
  }
}

@media (min-width: 1200px) {
  .cap-viewport {
    padding: 3rem 4rem;
  }

  .cap-display {
    grid-template-columns: 1fr 1.5fr;
    gap: 5rem;
  }

  .cap-right {
    padding: 2rem 2.5rem;
    min-height: 140px;
  }
}

@media (min-width: 1600px) {
  .cap-viewport {
    max-width: 1600px;
    padding: 4rem 6rem;
  }

  .cap-display {
    max-width: 1300px;
    gap: 6rem;
  }

  .cap-icon-wrap {
    width: 90px;
    height: 90px;
    border-radius: 22px;
  }

  .cap-right {
    padding: 2.5rem 3rem;
    min-height: 160px;
  }
}

@media (min-width: 2000px) {
  .cap-viewport {
    max-width: 1800px;
  }

  .cap-display {
    max-width: 1500px;
  }
}
</style>
