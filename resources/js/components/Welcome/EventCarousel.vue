<script setup lang="ts">
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps<{
    images: Array<{ id: number; image_path: string }>;
    altText: string;
}>();

const currentIndex = ref(0);
let timer: any = null;

function startAutoPlay() {
    stopAutoPlay();

    if (props.images.length > 1) {
        timer = setInterval(nextSlide, 5000); // Auto-scroll every 5 seconds
    }
}

function stopAutoPlay() {
    if (timer) {
        clearInterval(timer);
        timer = null;
    }
}

function nextSlide() {
    currentIndex.value = (currentIndex.value + 1) % props.images.length;
    startAutoPlay(); // Reset interval timer on manual click
}

function prevSlide() {
    currentIndex.value =
        (currentIndex.value - 1 + props.images.length) % props.images.length;
    startAutoPlay(); // Reset interval timer on manual click
}

function setSlide(index: number) {
    currentIndex.value = index;
    startAutoPlay(); // Reset interval timer on manual click
}

onMounted(() => {
    startAutoPlay();
});

onUnmounted(() => {
    stopAutoPlay();
});
</script>

<template>
    <div
        class="event-carousel-wrapper"
        @click.stop
        @mouseenter="stopAutoPlay"
        @mouseleave="startAutoPlay"
    >
        <!-- Single Image Display -->
        <div v-if="images.length === 1" class="single-image-box">
            <img
                :src="`/storage/${images[0].image_path}`"
                :alt="altText"
                class="carousel-image single-img"
            />
        </div>

        <!-- Carousel for Multiple Images -->
        <div v-else class="carousel-container">
            <!-- Images Slides -->
            <div class="slides-window">
                <div
                    v-for="(img, idx) in images"
                    v-show="idx === currentIndex"
                    :key="img.id"
                    class="slide-item"
                >
                    <img
                        :src="`/storage/${img.image_path}`"
                        :alt="`${altText} - Image ${idx + 1}`"
                        class="carousel-image"
                    />
                </div>
            </div>

            <!-- Controls (Always Visible) -->
            <button
                type="button"
                class="nav-btn prev-btn"
                aria-label="Previous image"
                @click="prevSlide"
            >
                <ChevronLeft :size="20" />
            </button>
            <button
                type="button"
                class="nav-btn next-btn"
                aria-label="Next image"
                @click="nextSlide"
            >
                <ChevronRight :size="20" />
            </button>

            <!-- Dots Indicators -->
            <div class="dots-indicators">
                <button
                    v-for="(_, idx) in images"
                    :key="idx"
                    type="button"
                    :class="['dot-btn', { active: idx === currentIndex }]"
                    :aria-label="`Go to slide ${idx + 1}`"
                    @click="setSlide(idx)"
                />
            </div>
        </div>
    </div>
</template>

<style scoped>
.event-carousel-wrapper {
    position: relative;
    width: 100%;
    margin-bottom: 1.25rem;
    user-select: none;
}

.single-image-box {
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--color-border);
    background-color: rgba(0, 0, 0, 0.01);
    max-height: 450px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.single-img {
    width: 100%;
    height: auto;
    max-height: 450px;
    object-fit: contain;
    display: block;
}

.carousel-container {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--color-border);
    background-color: rgba(0, 0, 0, 0.02);
    aspect-ratio: 16 / 9;
}

.slides-window {
    width: 100%;
    height: 100%;
    position: relative;
}

.slide-item {
    width: 100%;
    height: 100%;
    animation: fadeEffect 0.3s ease-in-out;
}

@keyframes fadeEffect {
    from {
        opacity: 0.7;
    }
    to {
        opacity: 1;
    }
}

.carousel-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* Nav Navigation Buttons - Always Visible */
.nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.45);
    backdrop-filter: blur(4px);
    color: #ffffff;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    z-index: 5;
}

.nav-btn:hover {
    background: rgba(0, 0, 0, 0.65);
    scale: 1.05;
}

.prev-btn {
    left: 12px;
}

.next-btn {
    right: 12px;
}

/* Indicators dots */
.dots-indicators {
    position: absolute;
    bottom: 12px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 6px;
    z-index: 5;
    background: rgba(0, 0, 0, 0.25);
    backdrop-filter: blur(4px);
    padding: 5px 10px;
    border-radius: 9999px;
}

.dot-btn {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    border: none;
    cursor: pointer;
    padding: 0;
    transition: all 0.2s ease;
}

.dot-btn.active {
    background: #ffffff;
    width: 14px;
    border-radius: 9999px;
}
</style>
