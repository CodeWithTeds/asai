<script setup lang="ts">
import { MapPin, Mail, Phone } from 'lucide-vue-next';

const currentYear = new Date().getFullYear();

const branches = [
    'Suite 92, West Gate Suites, Bldg. 286, Sampson Road, Central District Office, Subic Bay Freeport Zone',
    'J.R. Borja Extension (Great Spot Arcade), Camaman-an, Cagayan de Oro City',
];
</script>

<template>
    <footer id="contact" class="footer">
        <div class="footer-grid container">
            <div class="footer-brand">
                <div class="brand">
                    <img
                        src="/images/logo.png"
                        alt="ASAI Logo"
                        class="brand-logo"
                    />
                    <span>ASAI</span>
                </div>
                <p class="brand-tagline">
                    Annapolis Security Agency, Inc. — Transforming security for
                    a better business.
                </p>
            </div>

            <div class="footer-col">
                <h4 class="footer-heading">Head Office</h4>
                <p class="footer-line">
                    <MapPin :size="16" class="footer-icon" />
                    <span>
                        Unit 3008, 30th Floor, Jollibee Plaza,<br />
                        F. Ortigas Jr. Road, San Antonio, Pasig City
                    </span>
                </p>
            </div>

            <div class="footer-col">
                <h4 class="footer-heading">Branch Offices</h4>
                <p v-for="b in branches" :key="b" class="footer-line">
                    <MapPin :size="16" class="footer-icon" />
                    <span>{{ b }}</span>
                </p>
            </div>

            <div class="footer-col">
                <h4 class="footer-heading">Contact</h4>
                <a
                    href="mailto:annapolis082018@gmail.com"
                    class="footer-line link"
                >
                    <Mail :size="16" class="footer-icon" />
                    <span>annapolis082018@gmail.com</span>
                </a>
                <a href="tel:+639171756872" class="footer-line link">
                    <Phone :size="16" class="footer-icon" />
                    <span>0917-175-6872</span>
                </a>
            </div>
        </div>

        <div class="footer-base">
            <div class="container">
                <p>
                    © {{ currentYear }} Annapolis Security Agency, Inc. All
                    rights reserved.
                </p>
                <p class="reg">SEC Reg. CS201809381</p>
            </div>
        </div>
    </footer>
</template>

<style scoped>
.footer {
    background: var(--color-primary-dark);
    color: rgba(255, 255, 255, 0.85);
    padding-top: 3rem;
}

.footer-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    padding-bottom: 2.25rem;
}

.footer-brand .brand {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 1.25rem;
    color: #fff;
    margin-bottom: 0.65rem;
}

.brand-logo {
    height: 32px;
    width: auto;
    display: block;
}

.brand-tagline {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.88rem;
    line-height: 1.55;
    max-width: 320px;
}

.footer-heading {
    font-family: var(--font-sans);
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: var(--color-accent);
    margin-bottom: 0.75rem;
}

.footer-line {
    display: flex;
    gap: 0.5rem;
    align-items: flex-start;
    margin-bottom: 0.65rem;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.78);
    line-height: 1.45;
}

.footer-icon {
    color: var(--color-accent);
    flex-shrink: 0;
    margin-top: 2px;
}

.link {
    transition: color 0.2s ease;
}

.link:hover {
    color: #fff;
}

.footer-base {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding: 1.25rem 0;
    font-size: 0.78rem;
    color: rgba(255, 255, 255, 0.6);
}

.footer-base .container {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 0.35rem;
}

.reg {
    letter-spacing: 0.06em;
}

@media (min-width: 640px) {
    .footer-base .container {
        flex-direction: row;
        justify-content: space-between;
        text-align: left;
    }
}

@media (min-width: 768px) {
    .footer {
        padding-top: 4rem;
    }

    .footer-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 2.5rem;
        padding-bottom: 3rem;
    }

    .footer-brand .brand {
        font-size: 1.4rem;
        margin-bottom: 0.85rem;
    }

    .brand-logo {
        height: 36px;
    }

    .brand-tagline {
        font-size: 0.95rem;
    }

    .footer-heading {
        font-size: 0.78rem;
        letter-spacing: 0.18em;
        margin-bottom: 1rem;
    }

    .footer-line {
        gap: 0.6rem;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .footer-base {
        font-size: 0.82rem;
    }

    .reg {
        letter-spacing: 0.08em;
    }
}

@media (min-width: 900px) {
    .footer-grid {
        grid-template-columns: 1.1fr 1fr 1.2fr 1fr;
        gap: 2rem;
    }
}
</style>
