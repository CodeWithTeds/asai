<script setup lang="ts">
import { ref, computed } from 'vue'
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
const trackRef = ref<HTMLElement | null>(null)

const activeCap = computed(() => services[activeIndex.value] as (typeof services)[number])

function selectService(index: number) {
  activeIndex.value = index
}

function scrollCards(direction: 'left' | 'right') {
  if (!trackRef.value) return
  const cardWidth = trackRef.value.querySelector('.svc-card')?.clientWidth ?? 200
  const scrollAmount = cardWidth + 12 // card width + gap
  trackRef.value.scrollBy({
    left: direction === 'right' ? scrollAmount : -scrollAmount,
    behavior: 'smooth',
  })
}
</script>

<template>
  <section id="services" class="services-section">
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

    <div class="services-inner">
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
        <button class="carousel-btn carousel-btn--left" @click="scrollCards('left')" aria-label="Scroll left">
          <ChevronLeft :size="20" />
        </button>

        <div class="cards-track" ref="trackRef">
          <div
            v-for="(s, i) in services"
            :key="s.label"
            class="svc-card"
            :class="{ 'svc-card--active': i === activeIndex }"
            @click="selectService(i)"
          >
            <div class="svc-card-img">
              <img :src="s.img" :alt="s.label" />
            </div>
            <div class="svc-card-content">
              <component :is="s.icon" :size="16" class="svc-card-icon" />
              <span class="svc-card-label">{{ s.label }}</span>
            </div>
          </div>
        </div>

        <button class="carousel-btn carousel-btn--right" @click="scrollCards('right')" aria-label="Scroll right">
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

/* Full background */
.services-bg {
  position: absolute;
  inset: 0;
  z-index: 0;
}

.services-bg-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
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
}

/* Background crossfade */
.bg-fade-enter-active,
.bg-fade-leave-active {
  transition: opacity 0.6s ease;
}
.bg-fade-enter-from,
.bg-fade-leave-to {
  opacity: 0;
}

/* Text crossfade */
.text-fade-enter-active,
.text-fade-leave-active {
  transition: opacity 0.35s ease, transform 0.35s ease;
}
.text-fade-enter-from {
  opacity: 0;
  transform: translateY(12px);
}
.text-fade-leave-to {
  opacity: 0;
  transform: translateY(-12px);
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
.services-text {
  color: #fff;
  max-width: 600px;
}

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
.services-carousel {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  justify-self: end;
  max-width: 100%;
}

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
  transition: background-color 0.2s ease, transform 0.2s ease;
}

.carousel-btn:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: scale(1.1);
}

.cards-track {
  display: flex;
  gap: 0.75rem;
  overflow-x: auto;
  scroll-snap-type: x mandatory;
  -webkit-overflow-scrolling: touch;
  padding: 0.5rem 0;
  flex: 1;
}

.cards-track::-webkit-scrollbar {
  height: 0;
  display: none;
}

.svc-card {
  position: relative;
  flex: 0 0 160px;
  min-width: 160px;
  aspect-ratio: 3 / 4;
  border-radius: var(--radius-md);
  overflow: hidden;
  cursor: pointer;
  scroll-snap-align: start;
  border: 2px solid transparent;
  transition:
    transform 0.3s cubic-bezier(0.16, 1, 0.3, 1),
    border-color 0.3s ease,
    box-shadow 0.3s ease;
}

.svc-card:hover {
  transform: translateY(-4px) scale(1.02);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5);
}

.svc-card--active {
  border-color: var(--color-accent);
  box-shadow: 0 0 24px rgba(221, 179, 96, 0.35);
  transform: scale(1.03);
}

.svc-card-img {
  position: absolute;
  inset: 0;
}

.svc-card-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
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
    max-width: 540px;
  }

  .svc-card {
    flex: 0 0 165px;
    min-width: 165px;
  }
}

@media (min-width: 1200px) {
  .services-inner {
    padding: 6rem 4rem;
    gap: 4rem;
  }

  .services-carousel {
    max-width: 600px;
  }

  .svc-card {
    flex: 0 0 180px;
    min-width: 180px;
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
    max-width: 660px;
  }
}
</style>
