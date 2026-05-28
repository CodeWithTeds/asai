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
const typedText = ref('')
let typingTimer: ReturnType<typeof setInterval> | undefined
let ctx: gsap.Context | undefined

const activeCap = computed(() => capabilities[activeIndex.value] as (typeof capabilities)[number])

function typeText(text: string) {
  if (typingTimer) clearInterval(typingTimer)
  typedText.value = ''
  let i = 0
  typingTimer = setInterval(() => {
    if (i < text.length) {
      typedText.value = text.slice(0, i + 1)
      i++
    } else {
      if (typingTimer) clearInterval(typingTimer)
    }
  }, 16)
}

onMounted(() => {
  const el = sectionRef.value
  if (!el) return

  const totalItems = capabilities.length
  // Each item gets a "page" of scroll
  const scrollPerItem = 100 // vh per item

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
          const cap = capabilities[newIndex]
          if (cap) typeText(cap.text)
        }
      },
    })
  }, el)

  // Start with first item
  const first = capabilities[0]
  if (first) typeText(first.text)
})

onUnmounted(() => {
  if (typingTimer) clearInterval(typingTimer)
  ctx?.revert()
})
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

      <!-- Active capability display -->
      <div class="cap-display" :class="`cap-display--${activeCap.align}`">
        <div class="cap-left">
          <div class="cap-icon-wrap">
            <component :is="activeCap.icon" :size="36" />
          </div>
          <span class="cap-number">{{ String(activeIndex + 1).padStart(2, '0') }}</span>
          <h3 class="cap-title">{{ activeCap.title }}</h3>
        </div>

        <div class="cap-right">
          <p class="cap-typed">
            {{ typedText }}
            <span v-if="typedText.length < activeCap.text.length" class="cursor">|</span>
          </p>
        </div>
      </div>

      <!-- Progress dots -->
      <div class="cap-dots">
        <button
          v-for="(c, i) in capabilities"
          :key="i"
          class="cap-dot"
          :class="{ active: i === activeIndex, visited: i < activeIndex }"
          :aria-label="`Capability ${i + 1}: ${c.title}`"
        />
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
  padding: 2rem 1.5rem;
  max-width: var(--container-max);
  margin: 0 auto;
  position: relative;
}

.cap-header {
  text-align: center;
  margin-bottom: 3rem;
}

.cap-subtitle {
  color: var(--color-text-muted);
  font-size: 1.05rem;
  margin-top: 0.5rem;
}

/* Display area */
.cap-display {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
  width: 100%;
  align-items: center;
  transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

/* Zoom-in from different origins */
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
  0% {
    opacity: 0;
    transform: scale(0.85) translateX(-40px);
  }
  100% {
    opacity: 1;
    transform: scale(1) translateX(0);
  }
}

@keyframes zoomFromCenter {
  0% {
    opacity: 0;
    transform: scale(0.85);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

@keyframes zoomFromRight {
  0% {
    opacity: 0;
    transform: scale(0.85) translateX(40px);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

.cap-left {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.cap-icon-wrap {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 72px;
  height: 72px;
  border-radius: 18px;
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
  font-size: 3rem;
  font-weight: 800;
  color: rgba(74, 95, 128, 0.12);
  line-height: 1;
}

.cap-title {
  font-family: var(--font-sans);
  font-size: clamp(1.5rem, 3vw, 2.25rem);
  font-weight: 800;
  color: var(--color-primary-dark);
  letter-spacing: -0.02em;
}

.cap-right {
  padding: 1.5rem;
  border-left: 3px solid var(--color-accent);
  min-height: 120px;
  display: flex;
  align-items: center;
}

.cap-typed {
  font-size: 1.15rem;
  line-height: 1.8;
  color: var(--color-text-muted);
}

.cursor {
  display: inline-block;
  color: var(--color-accent);
  font-weight: 700;
  font-size: 1.3rem;
  animation: blink 0.75s step-end infinite;
}

@keyframes blink {
  0%, 100% { opacity: 1; }
  50% { opacity: 0; }
}

/* Progress dots */
.cap-dots {
  position: absolute;
  bottom: 2rem;
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
  border: none;
  cursor: default;
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
  .cap-display {
    grid-template-columns: 1fr 1.3fr;
    gap: 4rem;
  }

  .cap-right {
    padding: 2rem;
  }
}
</style>
