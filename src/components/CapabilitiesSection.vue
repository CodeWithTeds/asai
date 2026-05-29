<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue'
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

// Positions cycle: top-left, top-right, bottom-right, bottom-left
const positions = [
  { x: '5%', y: '12%' },
  { x: '55%', y: '12%' },
  { x: '55%', y: '55%' },
  { x: '5%', y: '55%' },
]

const sectionRef = ref<HTMLElement | null>(null)
let ctx: gsap.Context | undefined

onMounted(() => {
  const el = sectionRef.value
  if (!el) return

  ctx = gsap.context(() => {
    const totalItems = capabilities.length
    const zoomCanvas = el.querySelector('.cap-zoom-canvas') as HTMLElement
    const header = el.querySelector('.cap-header') as HTMLElement

    ScrollTrigger.create({
      trigger: el,
      start: 'top top',
      end: `+=${totalItems * 40}%`,
      pin: true,
      scrub: 0.4,
      onUpdate: (self) => {
        const progress = self.progress
        const currentIndex = Math.min(Math.floor(progress * totalItems), totalItems - 1)
        const itemProgress = (progress * totalItems) - currentIndex

        // Zoom the entire canvas as we scroll
        const baseScale = 1 + (progress * 1.5)
        if (zoomCanvas) {
          zoomCanvas.style.transform = `scale(${baseScale})`
        }

        // Fade header
        if (header) {
          if (progress > 0.03) {
            header.style.opacity = '0'
            header.style.transform = 'translate(-50%, -50%) scale(0.8)'
          } else {
            header.style.opacity = '1'
            header.style.transform = 'translate(-50%, -50%) scale(1)'
          }
        }

        // Show/hide items with zoom
        const items = el.querySelectorAll('.cap-item') as NodeListOf<HTMLElement>
        items.forEach((item, i) => {
          if (i === currentIndex) {
            item.classList.add('active')
            // Zoom the active item further based on item progress
            const itemScale = 1 + (itemProgress * 0.15)
            item.style.transform = `scale(${itemScale})`
          } else if (i < currentIndex) {
            item.classList.remove('active')
            item.classList.add('passed')
            item.style.transform = 'scale(0.8)'
          } else {
            item.classList.remove('active')
            item.classList.remove('passed')
            item.style.transform = 'scale(0.5)'
          }
        })

        // Update dots
        const dots = el.querySelectorAll('.cap-dot')
        dots.forEach((dot, i) => {
          dot.classList.toggle('active', i === currentIndex)
          dot.classList.toggle('visited', i < currentIndex)
        })
      },
    })
  }, el)
})

onUnmounted(() => {
  ctx?.revert()
})

function getPosition(index: number) {
  return positions[index % positions.length]
}
</script>

<template>
  <section id="capabilities" class="capabilities-section" ref="sectionRef">
    <div class="cap-viewport">
      <!-- Header -->
      <div class="cap-header">
        <span class="section-eyebrow">Agency Capability</span>
        <h2 class="section-title">Built on capability, governed by integrity</h2>
        <p class="cap-subtitle">Eight pillars that define how we operate, recruit, train, and protect.</p>
      </div>

      <!-- Zoom canvas - the whole thing scales up as you scroll -->
      <div class="cap-zoom-canvas">
        <div
          v-for="(cap, i) in capabilities"
          :key="i"
          class="cap-item"
          :style="{
            left: getPosition(i).x,
            top: getPosition(i).y,
          }"
        >
          <div class="cap-item-inner">
            <div class="cap-number-row">
              <div class="cap-icon-wrap">
                <component :is="cap.icon" :size="24" />
              </div>
              <span class="cap-number">{{ String(i + 1).padStart(2, '0') }}</span>
            </div>
            <h3 class="cap-title">{{ cap.title }}</h3>
            <p class="cap-text">{{ cap.text }}</p>
          </div>
        </div>
      </div>

      <!-- Dots -->
      <div class="cap-dots">
        <span
          v-for="(_c, i) in capabilities"
          :key="i"
          class="cap-dot"
        ></span>
      </div>
    </div>
  </section>
</template>

<style scoped>
.capabilities-section {
  width: 100%;
  height: 100vh;
  background: #0d1117;
  overflow: hidden;
}

.cap-viewport {
  width: 100%;
  height: 100vh;
  position: relative;
  overflow: hidden;
}

/* Header */
.cap-header {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%) scale(1);
  text-align: center;
  z-index: 10;
  transition: opacity 0.4s ease, transform 0.4s ease;
  width: 90%;
  max-width: 700px;
}

.cap-header .section-title {
  font-size: clamp(1.8rem, 3.5vw, 2.75rem);
  color: #fff;
}

.cap-subtitle {
  color: rgba(255, 255, 255, 0.6);
  font-size: clamp(0.9rem, 1.2vw, 1.1rem);
  margin-top: 0.75rem;
}

/* Zoom canvas - scales the entire content area */
.cap-zoom-canvas {
  position: absolute;
  inset: 0;
  transform-origin: center center;
  transition: transform 0.1s linear;
  will-change: transform;
}

/* Each capability item */
.cap-item {
  position: absolute;
  width: 38%;
  max-width: 450px;
  opacity: 0;
  transform: scale(0.5);
  transition: opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1), transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
  z-index: 5;
}

.cap-item.active {
  opacity: 1;
  z-index: 8;
}

.cap-item.passed {
  opacity: 0.15;
}

.cap-item-inner {
  padding: 1.5rem;
}

.cap-number-row {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 0.75rem;
}

.cap-icon-wrap {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background: var(--color-primary);
  color: #fff;
}

.cap-number {
  font-family: var(--font-display);
  font-size: clamp(2rem, 4vw, 3.5rem);
  font-weight: 800;
  color: rgba(255, 255, 255, 0.06);
  line-height: 1;
}

.cap-title {
  font-size: clamp(1.1rem, 2vw, 1.6rem);
  font-weight: 800;
  color: #fff;
  margin-bottom: 0.5rem;
  letter-spacing: -0.02em;
}

.cap-text {
  font-size: clamp(0.85rem, 1.1vw, 1rem);
  line-height: 1.7;
  color: rgba(255, 255, 255, 0.7);
}

/* Dots */
.cap-dots {
  position: absolute;
  bottom: 1.5rem;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 0.5rem;
  z-index: 20;
}

.cap-dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.2);
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.cap-dot.visited {
  background: var(--color-primary-soft);
}

.cap-dot.active {
  background: var(--color-accent);
  transform: scale(1.4);
}

/* Mobile */
@media (max-width: 767px) {
  .cap-item {
    width: 80%;
    left: 10% !important;
    top: 25% !important;
  }
}
</style>
