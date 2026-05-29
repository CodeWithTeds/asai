import { onMounted, onUnmounted, ref } from 'vue';

export function useScrollReveal(threshold = 0.15) {
    const containerRef = ref<HTMLElement | null>(null);
    let observer: IntersectionObserver | null = null;

    onMounted(() => {
        if (!containerRef.value) {
return;
}

        observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                        observer?.unobserve(entry.target);
                    }
                });
            },
            { threshold, rootMargin: '0px 0px -60px 0px' },
        );

        const elements = containerRef.value.querySelectorAll('.reveal');
        elements.forEach((el) => observer?.observe(el));
    });

    onUnmounted(() => {
        observer?.disconnect();
    });

    return { containerRef };
}
