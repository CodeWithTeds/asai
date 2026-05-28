<script setup lang="ts">
import { ArrowRight, ShieldCheck, Award, Users } from '@lucide/vue'
</script>

<template>
  <section id="top" class="hero">
    <div class="container hero-grid">
      <div class="hero-visual">
        <img src="/images/hero.png" alt="ASAI security operations" class="hero-img" />
      </div>

      <div class="hero-content">
        <span class="eyebrow">Annapolis Security Agency, Inc.</span>
        <h1 class="hero-title">
          Transforming security for a
          <span class="accent">better business.</span>
        </h1>
        <p class="hero-lead">
          A service-oriented corporation putting client needs and satisfaction as our utmost
          priority — backed by strong financial capabilities and highly skilled security
          professionals across Luzon, Visayas, and Mindanao.
        </p>

        <div class="hero-cta">
          <a href="#services" class="btn-primary">
            Explore services
            <ArrowRight :size="16" />
          </a>
          <a href="#about" class="btn-ghost">Learn more</a>
        </div>

        <ul class="hero-stats">
          <li>
            <Award :size="20" class="stat-icon" />
            <div>
              <strong>10+ years</strong>
              <span>of trusted operations</span>
            </div>
          </li>
          <li>
            <Users :size="20" class="stat-icon" />
            <div>
              <strong>Nationwide</strong>
              <span>Luzon, Visayas, Mindanao</span>
            </div>
          </li>
          <li>
            <ShieldCheck :size="20" class="stat-icon" />
            <div>
              <strong>SEC Reg.</strong>
              <span>CS201809381</span>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </section>
</template>

<style scoped>
.hero {
  padding: 4rem 0 5rem;
  position: relative;
  overflow: hidden;
}

.hero-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 3rem;
  align-items: center;
  position: relative;
}

.hero-visual {
  order: 2;
  position: relative;
  display: flex;
  justify-content: flex-end;
}

.hero-img {
  width: 100%;
  height: auto;
  display: block;
}

.hero-content {
  order: 1;
}

.eyebrow {
  display: inline-block;
  font-size: 0.78rem;
  font-weight: 600;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: var(--color-accent);
  margin-bottom: 1rem;
}

.hero-title {
  font-size: clamp(2rem, 5vw, 3.5rem);
  margin-bottom: 1.25rem;
}

.hero-title .accent {
  color: var(--color-accent);
  font-style: italic;
}

.hero-lead {
  color: var(--color-text-muted);
  font-size: 1.1rem;
  max-width: 560px;
  margin-bottom: 2rem;
}

.hero-cta {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
  margin-bottom: 2.5rem;
}

.btn-primary {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: var(--color-primary);
  color: #fff;
  padding: 0.85rem 1.4rem;
  border-radius: var(--radius-sm);
  font-weight: 600;
  transition: background-color 0.2s ease, transform 0.2s ease;
}

.btn-primary:hover {
  background: var(--color-primary-dark);
  transform: translateY(-2px);
}

.btn-ghost {
  display: inline-flex;
  align-items: center;
  padding: 0.85rem 1.4rem;
  border-radius: var(--radius-sm);
  font-weight: 600;
  color: var(--color-primary-dark);
  border: 1px solid var(--color-border);
  background: #fff;
  transition: border-color 0.2s ease;
}

.btn-ghost:hover {
  border-color: var(--color-primary);
}

.hero-stats {
  list-style: none;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 1.25rem;
  padding-top: 2rem;
  border-top: 1px solid var(--color-border);
}

.hero-stats li {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.stat-icon {
  color: var(--color-accent);
  flex-shrink: 0;
}

.hero-stats strong {
  display: block;
  font-weight: 700;
  color: var(--color-primary-dark);
  font-size: 1rem;
}

.hero-stats span {
  font-size: 0.85rem;
  color: var(--color-text-muted);
}

@media (min-width: 900px) {
  .hero {
    padding: 5rem 0 7rem;
  }
  .hero-grid {
    grid-template-columns: 1fr 1fr;
    gap: 20%;
  }
  .hero-content {
    order: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }
  .hero-visual {
    order: 2;
    justify-content: flex-end;
  }
}
</style>
