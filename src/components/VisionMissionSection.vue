<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { Eye, Target, Sparkles } from '@lucide/vue'

const visionPoints = [
  'A world-class Security Service Company',
  'An ISO-Certified Security Management Company',
  'To initiate the professionalization of enterprise security practice in the country — creating an environment of professional acceptance of holistic security concepts.',
  'To develop and maintain a secure environment where maximum productivity is assured and people may enjoy the fruits of their labor free of anxiety and fear.',
]

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
    { threshold: 0.2 }
  )
  observer.observe(el)

  onUnmounted(() => observer.disconnect())
})
</script>

<template>
  <section id="vision" class="section vision-mission" ref="sectionRef" :class="{ visible: isVisible }">
    <div class="container">
      <div class="vm-header">
        <Sparkles :size="20" class="vm-header-icon" />
        <span class="section-eyebrow">Our Purpose</span>
      </div>

      <div class="vm-grid">
        <article class="vm-card vm-card--vision">
          <div class="vm-card-glow"></div>
          <div class="vm-icon">
            <Eye :size="24" />
          </div>
          <h2 class="vm-title">Vision</h2>
          <ul class="vm-list">
            <li v-for="(point, i) in visionPoints" :key="i" :style="{ transitionDelay: `${300 + i * 100}ms` }">
              <span class="vm-bullet"></span>
              <span>{{ point }}</span>
            </li>
          </ul>
        </article>

        <article class="vm-card vm-card--mission">
          <div class="vm-card-glow vm-card-glow--accent"></div>
          <div class="vm-icon vm-icon--accent">
            <Target :size="24" />
          </div>
          <h2 class="vm-title">Mission</h2>
          <p class="vm-text">
            To upgrade the level of security service by applying an integrated system approach for
            greater specialization — adapting to current and evolving security and safety
            challenges, making us a trustworthy partner for business development of our current and
            future clients.
          </p>
        </article>
      </div>
    </div>
  </section>
</template>

<style scoped>
.vision-mission {
  background: linear-gradient(180deg, #f8fafb 0%, #fff 100%);
  overflow: hidden;
}

.vm-header {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 2.5rem;
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.visible .vm-header {
  opacity: 1;
  transform: translateY(0);
}

.vm-header-icon {
  color: var(--color-accent);
}

.vm-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
}

/* Cards */
.vm-card {
  position: relative;
  padding: 2.5rem;
  border-radius: var(--radius-lg);
  overflow: hidden;
  opacity: 0;
  transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
}

.vm-card--vision {
  background: #fff;
  border: 1px solid var(--color-border);
  transform: translateX(-50px) scale(0.95);
  transition-delay: 0.15s;
}

.vm-card--mission {
  background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
  border: none;
  transform: translateX(50px) scale(0.95);
  transition-delay: 0.3s;
}

.visible .vm-card--vision,
.visible .vm-card--mission {
  opacity: 1;
  transform: translateX(0) scale(1);
}

.vm-card:hover {
  box-shadow: 0 20px 50px rgba(30, 123, 242, 0.15);
}

.vm-card--mission:hover {
  box-shadow: 0 20px 50px rgba(30, 123, 242, 0.35);
}

/* Glow effect */
.vm-card-glow {
  position: absolute;
  top: -80px;
  right: -80px;
  width: 200px;
  height: 200px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(30, 123, 242, 0.12), transparent 70%);
  pointer-events: none;
  transition: transform 0.6s ease;
}

.vm-card:hover .vm-card-glow {
  transform: scale(1.3);
}

.vm-card-glow--accent {
  background: radial-gradient(circle, rgba(221, 179, 96, 0.3), transparent 70%);
}

/* Icon */
.vm-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 52px;
  height: 52px;
  border-radius: 14px;
  background: rgba(30, 123, 242, 0.1);
  color: var(--color-primary);
  margin-bottom: 1.25rem;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.vm-card:hover .vm-icon {
  transform: scale(1.1) rotate(-3deg);
  box-shadow: 0 8px 20px rgba(30, 123, 242, 0.2);
}

.vm-icon--accent {
  background: rgba(221, 179, 96, 0.2);
  color: var(--color-accent);
}

.vm-card:hover .vm-icon--accent {
  box-shadow: 0 8px 20px rgba(221, 179, 96, 0.3);
}

/* Title */
.vm-title {
  font-size: clamp(1.4rem, 2.5vw, 1.8rem);
  margin-bottom: 1.25rem;
}

.vm-card--mission .vm-title {
  color: #fff;
}

/* Vision list */
.vm-list {
  list-style: none;
  display: grid;
  gap: 0.85rem;
}

.vm-list li {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  color: var(--color-text-muted);
  font-size: 0.98rem;
  line-height: 1.6;
  opacity: 0;
  transform: translateX(-20px);
  transition: opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1), transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

.visible .vm-list li {
  opacity: 1;
  transform: translateX(0);
}

.vm-bullet {
  flex-shrink: 0;
  width: 8px;
  height: 8px;
  margin-top: 0.5em;
  border-radius: 3px;
  background: var(--color-accent);
  transition: transform 0.3s ease;
}

.vm-list li:hover .vm-bullet {
  transform: scale(1.5) rotate(45deg);
}

/* Mission text */
.vm-text {
  color: rgba(255, 255, 255, 0.9);
  font-size: 1.05rem;
  line-height: 1.8;
}

@media (min-width: 900px) {
  .vm-grid {
    grid-template-columns: 1.1fr 1fr;
    gap: 2rem;
  }

  .vm-card {
    padding: 3rem;
  }
}
</style>
