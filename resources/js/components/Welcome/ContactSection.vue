<script setup lang="ts">
import { ref } from 'vue';
import { useScrollReveal } from '@/composables/useScrollReveal';
import { CheckCircle2 } from 'lucide-vue-next';

const { containerRef } = useScrollReveal();

const form = ref({
    name: '',
    email: '',
    message: '',
});

const errors = ref({
    name: '',
    email: '',
    message: '',
});

const isSubmitting = ref(false);
const isSuccess = ref(false);

function validate() {
    let isValid = true;
    errors.value = { name: '', email: '', message: '' };

    if (!form.value.name.trim()) {
        errors.value.name = 'Name is required.';
        isValid = false;
    }

    if (!form.value.email.trim()) {
        errors.value.email = 'Email is required.';
        isValid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.email)) {
        errors.value.email = 'Please enter a valid email address.';
        isValid = false;
    }

    if (!form.value.message.trim()) {
        errors.value.message = 'Message is required.';
        isValid = false;
    }

    return isValid;
}

function handleSubmit() {
    if (!validate()) return;

    isSubmitting.value = true;

    // Simulate form submission
    setTimeout(() => {
        isSubmitting.value = false;
        isSuccess.value = true;
        form.value = { name: '', email: '', message: '' };

        // Reset success state after 6 seconds
        setTimeout(() => {
            isSuccess.value = false;
        }, 6000);
    }, 1200);
}
</script>

<template>
    <section class="section contact-section" ref="containerRef" id="consultation">
        <!-- Premium Background Design: Grid and Glows -->
        <div class="bg-grid-overlay"></div>
        <div class="bg-glow bg-glow-blue"></div>
        <div class="bg-glow bg-glow-gold"></div>

        <div class="contact-grid container">
            <!-- Left Column: Typography & Text -->
            <div class="reveal-left reveal contact-info">
                <h2 class="contact-title">
                    SECURE YOUR <br />
                    <span class="highlight">BUSINESS</span>
                </h2>
                <p class="contact-lead">
                    Protect your assets, operations, and personnel with Annapolis Security Agency, Inc. (ASAI). 
                    Reach out to our security specialists to discuss a tailored protection plan today.
                </p>
                <a href="#services" class="capabilities-link">
                    LEARN MORE
                </a>
            </div>

            <!-- Right Column: Form Inputs (directly on background) -->
            <div class="reveal-right reveal contact-form-wrapper">
                <!-- Success State -->
                <div v-if="isSuccess" class="success-alert">
                    <div class="success-header">
                        <CheckCircle2 :size="28" class="success-icon" />
                        <h4 class="success-title">Message Sent!</h4>
                    </div>
                    <p class="success-text">
                        Thank you for reaching out. A security consultant will review your message and contact you shortly.
                    </p>
                </div>

                <!-- Form State -->
                <form v-else @submit.prevent="handleSubmit" class="contact-form">
                    <div class="form-group">
                        <input
                            type="text"
                            id="contact-name"
                            v-model="form.name"
                            placeholder="Name"
                            :disabled="isSubmitting"
                            class="form-input"
                            :class="{ 'has-error': errors.name }"
                        />
                        <span v-if="errors.name" class="error-text">{{ errors.name }}</span>
                    </div>

                    <div class="form-group">
                        <input
                            type="email"
                            id="contact-email"
                            v-model="form.email"
                            placeholder="Email"
                            :disabled="isSubmitting"
                            class="form-input"
                            :class="{ 'has-error': errors.email }"
                        />
                        <span v-if="errors.email" class="error-text">{{ errors.email }}</span>
                    </div>

                    <div class="form-group">
                        <textarea
                            id="contact-message"
                            v-model="form.message"
                            placeholder="Message"
                            rows="4"
                            :disabled="isSubmitting"
                            class="form-textarea"
                            :class="{ 'has-error': errors.message }"
                        ></textarea>
                        <span v-if="errors.message" class="error-text">{{ errors.message }}</span>
                    </div>

                    <button type="submit" class="btn-submit" :disabled="isSubmitting">
                        <span v-if="isSubmitting" class="spinner"></span>
                        <span v-else>SUBMIT</span>
                    </button>
                </form>
            </div>
        </div>
    </section>
</template>

<style scoped>
.contact-section {
    background: #080914; /* Brand Navy-Black Base */
    color: #ffffff;
    padding: 7rem 0;
    overflow: hidden;
    position: relative;
}

/* 1. Background Grid Design Overlay */
.bg-grid-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: 
        linear-gradient(to right, rgba(255, 255, 255, 0.015) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(255, 255, 255, 0.015) 1px, transparent 1px);
    background-size: 50px 50px;
    pointer-events: none;
    z-index: 1;
}

/* 2. Soft Radial Glowing Corner Highlights */
.bg-glow {
    position: absolute;
    width: 600px;
    height: 600px;
    border-radius: 50%;
    filter: blur(130px);
    pointer-events: none;
    z-index: 1;
}

.bg-glow-blue {
    background: #1d219d; /* Brand royal blue glow */
    top: -200px;
    left: -200px;
    opacity: 0.16;
}

.bg-glow-gold {
    background: #daa520; /* Brand gold glow */
    bottom: -200px;
    right: -200px;
    opacity: 0.09;
}

.contact-grid {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: 1fr;
    gap: 4rem;
    align-items: center;
}

.contact-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.contact-title {
    font-family: var(--font-sans);
    font-size: clamp(2.2rem, 5vw, 3.2rem);
    font-weight: 800;
    line-height: 1.15;
    letter-spacing: -0.02em;
    color: #ffffff;
    margin-bottom: 1.5rem;
    text-transform: uppercase;
}

.contact-title .highlight {
    background: linear-gradient(135deg, #ffffff 40%, var(--color-accent-soft) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.contact-lead {
    font-size: 1rem;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.65);
    max-width: 480px;
    margin-bottom: 2rem;
}

.capabilities-link {
    display: inline-flex;
    align-items: center;
    color: #ffffff;
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    border-bottom: 2px solid rgba(255, 255, 255, 0.25);
    padding-bottom: 6px;
    transition: all 0.3s ease;
}

.capabilities-link:hover {
    color: var(--color-accent-soft);
    border-color: var(--color-accent-soft);
}

/* Form Styles */
.contact-form-wrapper {
    width: 100%;
}

.contact-form {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    max-width: 500px;
    margin: 0 auto;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
    width: 100%;
}

.form-input,
.form-textarea {
    width: 100%;
    background: #ffffff;
    border: 1px solid transparent;
    border-radius: var(--radius-sm);
    padding: 0.9rem 1.15rem;
    font-size: 0.95rem;
    color: #111827;
    outline: none;
    transition: all 0.25s ease;
    font-family: var(--font-sans);
}

.form-textarea {
    resize: none;
}

.form-input:focus,
.form-textarea:focus {
    border-color: var(--color-accent-soft);
    box-shadow: 0 0 0 3px rgba(218, 165, 32, 0.22);
}

.form-input.has-error,
.form-textarea.has-error {
    border-color: #ef4444;
}

.error-text {
    color: #f87171;
    font-size: 0.8rem;
    font-weight: 500;
}

/* Tan Submit Button - Mockup Aligned */
.btn-submit {
    align-self: flex-start;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #ebdcb9; /* Beige/Tan */
    color: #080914; /* Navy text */
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 0.2em;
    padding: 0.95rem 2.25rem;
    border-radius: var(--radius-sm);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    cursor: pointer;
    min-width: 140px;
    border: none;
}

.btn-submit:hover:not(:disabled) {
    background: var(--color-accent-soft);
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(218, 165, 32, 0.2);
}

.btn-submit:active:not(:disabled) {
    transform: translateY(0);
}

.btn-submit:disabled {
    opacity: 0.65;
    cursor: not-allowed;
}

/* Success State styling */
.success-alert {
    background: rgba(218, 165, 32, 0.08);
    border: 1px solid rgba(218, 165, 32, 0.25);
    padding: 2rem;
    border-radius: var(--radius-md);
    animation: fadeIn 0.4s ease-out;
    max-width: 500px;
    margin: 0 auto;
}

.success-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.success-icon {
    color: var(--color-accent-soft);
}

.success-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #ffffff;
}

.success-text {
    font-size: 0.92rem;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.75);
}

.spinner {
    width: 18px;
    height: 18px;
    border: 2px solid rgba(8, 9, 20, 0.25);
    border-top-color: #080914;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (min-width: 900px) {
    .contact-grid {
        grid-template-columns: 1.1fr 1fr;
        gap: 6rem;
    }
    .contact-form {
        margin: 0;
    }
    .success-alert {
        margin: 0;
    }
}
</style>
