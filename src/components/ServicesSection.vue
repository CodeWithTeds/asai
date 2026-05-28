<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
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
  ChevronLeft,
  ChevronRight,
} from '@lucide/vue'

const services = [
  { icon: ShoppingBag, label: 'Retail / Mall Security', img: '/images/card1.png', desc: 'Protecting retail spaces and shopping malls with trained personnel ensuring safety for shoppers and staff alike.' },
  { icon: Headphones, label: 'BPO Security', img: '/images/card2.png', desc: 'Securing business process outsourcing facilities with round-the-clock monitoring and access control systems.' },
  { icon: Plane, label: 'Aviation Security', img: '/images/card3.png', desc: 'Comprehensive aviation security services meeting international standards for airports and airline operations.' },
  { icon: Hotel, label: 'Hotel Security', img: '/images/card1.png', desc: 'Discreet yet effective security solutions for hospitality establishments, ensuring guest safety and comfort.' },
  { icon: Factory, label: 'Industrial Security', img: '/images/card2.png', desc: 'Safeguarding industrial facilities, warehouses, and manufacturing plants with specialized security protocols.' },
  { icon: Users2, label: 'Guard Force Management', img: '/images/card3.png', desc: 'End-to-end management of security personnel including deployment, scheduling, and performance monitoring.' },
  { icon: CalendarDays, label: 'Event Security', img: '/images/card1.png', desc: 'Professional event security for corporate gatherings, concerts, and public events of any scale.' },
  { icon: UserCheck, label: 'VIP Protection Services', img: '/images/card2.png', desc: 'Executive protection and close-in security for high-profile individuals and dignitaries.' },
  { icon: ClipboardList, label: 'Security Consultancy', img: '/images/card3.png', desc: 'Expert consultancy for casino, hotel, retail, logistics and supply chain security operations.' },
  { icon: Dog, label: 'K9 Services', img: '/images/card1.png', desc: 'Trained K9 units for detection, patrol, and specialized security operations.' },
  { icon: Leaf, label: 'EOHS Assessment', img: '/images/card2.png', desc: 'Environmental, occupational, health and safety assessments ensuring regulatory compliance.' },
  { icon: Search, label: 'Security Survey', img: '/images/card3.png', desc: 'Comprehensive security surveys identifying vulnerabilities and recommending countermeasures.' },
  { icon: ShieldAlert, label: 'Vulnerability Assessment', img: '/images/card1.png', desc: 'In-depth vulnerability analysis of physical and operational security infrastructure.' },
  { icon: UserRoundCheck, label: 'VIP Protection', img: '/images/card2.png', desc: 'Dedicated close protection services with trained operatives for personal security.' },
  { icon: Camera, label: 'E-Guarding', img: '/images/card3.png', desc: 'Technology-driven remote monitoring and electronic surveillance solutions.' },
]

const activeIndex = ref(0)
const expanded = ref(false)
const trackRef = ref<HTMLElement | null>(null)
const removedIndices = ref<Set<number>>(new Set())

const activeCap = computed(() => {
  return services[activeIndex.value] as (typeof services)[number]
})

const visibleServices = computed(() =>
  services.filter((_, i) => !removedIndices.value.has(i))
)

function selectService(originalIndex: number) {
  // Set as active (changes background + text)
  activeIndex.value = originalIndex
  // Remove the card from the list
  removedIndices.value.add(originalIndex)
}

function collapseView() {
  expanded.value = false
}

function scrollCards(direction: 'left' | 'right') {
  if (!trackRef.value) return
  const cardWidth = trackRef.value.querySelector('.svc-card')?.clientWidth ?? 200
  const scrollAmount = cardWidth + 12
  trackRef.value.scrollBy({
    left: direction === 'right' ? scrollAmount : -scrollAmount,
    behavior: 'smooth',
  })
}

// Scroll-triggered entrance animation
const sectionRef = ref<HTMLElement | null>(null)
const isVisible = ref(false)

onMounted(() => {
  const el = sectionRef.value
  if (!el) return

  const observer = new IntersectionObserver(
    ([entry]) => {
      if (entry && entry.isIntersecting) {
        isVisible.value = true
        observer.disconnect()
      }
    },
    { threshold: 0.15 }
  )
  observer.observe(el)

  onUnmounted(() => observer.disconnect())
})
</script>

<template>
  <section id="services" class="services-section" ref="sectionRef" :class="{ 'is-visible': isVisible }">
    <!-- Full background image -->
    <div class="services-bg">
      <Transition name="bg-fade" mode="out-in">
        <img
          :key="activeIndex"
          :src="activeCap.img"
          :alt="activeCap.label"
          class="services-bg-img"
        />
      </Transition>
      <div class="services-bg-overlay"></div>
    </div>

    <div class="services-inner" @click="expanded ? collapseView() : null">
      <!-- Text content (changes based on active card) -->
      <div class="services-text">
        <span class="section-eyebrow">Services Offered</span>
        <Transition name="text-fade" mode="out-in">
          <div :key="activeIndex">
            <h2 class="services-title">{{ activeCap.label }}</h2>
            <p class="services-lead">{{ activeCap.desc }}</p>
          </div>
        </Transition>
      </div>

      <!-- Horizontal cards row (3 visible, scroll right) -->
      <div class="services-carousel">
        <button class="carousel-btn carousel-btn--left" @click.stop="scrollCards('left')" aria-label="Scroll left">
          <ChevronLeft :size="20" />
        </button>

        <div class="cards-track" ref="trackRef">
          <TransitionGroup name="card-remove">
            <div
              v-for="s in visibleServices"
              :key="s.label"
              class="svc-card"
              @click.stop="selectService(services.indexOf(s))"
            >
              <div class="svc-card-img">
                <img :src="s.img" :alt="s.label" />
              </div>
              <div class="svc-card-content">
                <component :is="s.icon" :size="16" class="svc-card-icon" />
                <span class="svc-card-label">{{ s.label }}</span>
              </div>
            </div>
          </TransitionGroup>
        </div>

        <button class="carousel-btn carousel-btn--right" @click.stop="scrollCards('right')" aria-label="Scroll right">
          <ChevronRight :size="20" />
        </button>
      </div>
    </div>
  </section>
</template>

<style scoped>
.services-section {
  position: relative;
  min-height: 100vh;
  overflow: hidden;
  display: flex;
  align-items: center;
}

/* ===== Entrance animations ===== */
.services-bg-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  transform: scale(1.1);
  transition: transform 1.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.services-section.is-visible .services-bg-img {
  transform: scale(1);
}

.services-text {
  color: #fff;
  max-width: 600px;
  opacity: 0;
  transform: translateX(-40px);
  transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s, transform 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s;
}

.services-section.is-visible .services-text {
  opacity: 1;
  transform: translateX(0);
}

.services-carousel {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  justify-self: end;
  max-width: 100%;
  opacity: 0;
  transform: translateX(40px);
  transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.4s, transform 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.4s;
}

.services-section.is-visible .services-carousel {
  opacity: 1;
  transform: translateX(0);
}

/* Full background */
.services-bg {
  position: absolute;
  inset: 0;
  z-index: 0;
}

.services-bg-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    to bottom,
    rgba(0, 0, 0, 0.7) 0%,
    rgba(0, 0, 0, 0.5) 50%,
    rgba(0, 0, 0, 0.8) 100%
  );
  transition: opacity 0.4s ease;
}

/* Background crossfade */
.bg-fade-enter-active,
.bg-fade-leave-active {
  transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}
.bg-fade-enter-from,
.bg-fade-leave-to {
  opacity: 0;
}

/* Text crossfade */
.text-fade-enter-active,
.text-fade-leave-active {
  transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1), transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.text-fade-enter-from {
  opacity: 0;
  transform: translateY(16px) scale(0.97);
}
.text-fade-leave-to {
  opacity: 0;
  transform: translateY(-16px) scale(0.97);
}

/* Card remove transition */
.card-remove-enter-active {
  transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

.card-remove-leave-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  position: absolute;
}

.card-remove-enter-from {
  opacity: 0;
  transform: scale(0.8) rotateY(10deg);
}

.card-remove-leave-to {
  opacity: 0;
  transform: scale(0.7) rotateY(-15deg) translateY(-30px);
}

/* Inner layout */
.services-inner {
  position: relative;
  z-index: 1;
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
  padding: 4rem 1.5rem;
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
  align-items: center;
}

/* Text */
.services-title {
  font-size: clamp(2rem, 4vw, 3.5rem);
  color: #fff;
  margin-bottom: 0.75rem;
}

.services-lead {
  font-size: clamp(1rem, 1.3vw, 1.15rem);
  color: rgba(255, 255, 255, 0.75);
  line-height: 1.7;
}

/* Carousel */
.carousel-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  color: #fff;
  flex-shrink: 0;
  transition: background-color 0.25s ease, transform 0.25s ease, box-shadow 0.25s ease;
}

.carousel-btn:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: scale(1.15);
  box-shadow: 0 4px 16px rgba(221, 179, 96, 0.25);
}

.cards-track {
  display: flex;
  gap: 0.75rem;
  overflow-x: auto;
  scroll-snap-type: x mandatory;
  -webkit-overflow-scrolling: touch;
  padding: 0.5rem 0;
  flex: 1;
  perspective: 1000px;
}

.cards-track::-webkit-scrollbar {
  height: 0;
  display: none;
}

.svc-card {
  position: relative;
  flex: 0 0 200px;
  min-width: 200px;
  aspect-ratio: 3 / 4;
  border-radius: var(--radius-md);
  overflow: hidden;
  cursor: pointer;
  scroll-snap-align: start;
  border: 2px solid transparent;
  transition:
    transform 0.35s cubic-bezier(0.16, 1, 0.3, 1),
    border-color 0.3s ease,
    box-shadow 0.35s ease;
}

.svc-card:hover {
  transform: translateY(-6px) scale(1.04);
  box-shadow: 0 16px 40px rgba(0, 0, 0, 0.5);
  border-color: rgba(221, 179, 96, 0.4);
}

.svc-card:active {
  transform: scale(0.96);
  transition-duration: 0.1s;
}

.svc-card-img {
  position: absolute;
  inset: 0;
}

.svc-card-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.svc-card:hover .svc-card-img img {
  transform: scale(1.08);
}

.svc-card-content {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 0.85rem;
  background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, transparent 100%);
  display: flex;
  align-items: center;
  gap: 0.4rem;
  transform: translateY(4px);
  transition: transform 0.3s ease;
}

.svc-card:hover .svc-card-content {
  transform: translateY(0);
}

.svc-card-icon {
  color: var(--color-accent);
  flex-shrink: 0;
}

.svc-card-label {
  font-size: 0.75rem;
  font-weight: 600;
  color: #fff;
  line-height: 1.3;
}

/* Responsive */
@media (min-width: 768px) {
  .services-inner {
    grid-template-columns: 1fr auto;
    padding: 5rem 2.5rem;
    gap: 3rem;
  }

  .services-carousel {
    max-width: 660px;
  }

  .svc-card {
    flex: 0 0 200px;
    min-width: 200px;
  }
}

@media (min-width: 1200px) {
  .services-inner {
    padding: 6rem 4rem;
    gap: 4rem;
  }

  .services-carousel {
    max-width: 750px;
  }

  .svc-card {
    flex: 0 0 230px;
    min-width: 230px;
  }

  .carousel-btn {
    width: 44px;
    height: 44px;
  }
}

@media (min-width: 1600px) {
  .services-inner {
    max-width: 1600px;
  }

  .services-carousel {
    max-width: 820px;
  }
}
</style>
