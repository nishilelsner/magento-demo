define([
    'jquery'
], function ($) {
    'use strict';

    return function (config, element) {
        const $section = $(element);
        const $track = $section.find('.new-arrivals-track');
        const $cards = $section.find('.new-arrivals-card');
        const $btnPrev = $section.find('.new-arrivals-arrow-left');
        const $btnNext = $section.find('.new-arrivals-arrow-right');
        const $dotsContainer = $section.find('.new-arrivals-dots');

        let productCount = parseInt($section.data('product-count'));
        let currentIndex = 0;
        let isSliderActive = false;
        let autoScrollInterval;

        function init() {
            checkSliderStatus();
            $(window).on('resize', checkSliderStatus);

            $btnNext.on('click', () => moveSlider(1));
            $btnPrev.on('click', () => moveSlider(-1));

            // Pause auto-scroll on hover
            $section.on('mouseenter', stopAutoScroll);
            $section.on('mouseleave', startAutoScroll);
        }

        function checkSliderStatus() {
            const width = $(window).width();
            const isMobile = width <= 768;
            const threshold = isMobile ? 2 : 4;

            if (productCount > threshold) {
                if (!isSliderActive) activateSlider();
            } else {
                if (isSliderActive) deactivateSlider(isMobile);
            }
        }

        function activateSlider() {
            isSliderActive = true;
            $section.removeClass('no-slider no-slider-mobile');
            createDots();
            updateSliderPosition();
            startAutoScroll();
        }

        function deactivateSlider(isMobile) {
            isSliderActive = false;
            stopAutoScroll();
            $section.addClass(isMobile ? 'no-slider-mobile' : 'no-slider');
            $track.css('transform', 'none');
            $dotsContainer.empty();
        }

        function createDots() {
            $dotsContainer.empty();
            const width = $(window).width();
            const visibleCards = width <= 768 ? 2 : 4;
            const totalDots = productCount - visibleCards + 1;

            if (totalDots <= 1) return;

            for (let i = 0; i < totalDots; i++) {
                const $dot = $('<button class="new-arrivals-dot"></button>');
                if (i === 0) $dot.addClass('active');
                $dot.on('click', () => {
                    currentIndex = i;
                    updateSliderPosition();
                });
                $dotsContainer.append($dot);
            }
        }

        function moveSlider(direction) {
            const width = $(window).width();
            const visibleCards = width <= 768 ? 2 : 4;
            const maxIndex = productCount - visibleCards;

            currentIndex += direction;

            // Infinite scroll logic
            if (currentIndex > maxIndex) currentIndex = 0;
            if (currentIndex < 0) currentIndex = maxIndex;

            updateSliderPosition();
        }

        function updateSliderPosition() {
            const cardWidth = $cards.outerWidth(true);
            const offset = -(currentIndex * cardWidth);
            $track.css('transform', `translateX(${offset}px)`);

            // Update dots
            $dotsContainer.find('.new-arrivals-dot').removeClass('active')
                .eq(currentIndex).addClass('active');
        }

        function startAutoScroll() {
            stopAutoScroll();
            if (isSliderActive) {
                autoScrollInterval = setInterval(() => moveSlider(1), 4000);
            }
        }

        function stopAutoScroll() {
            if (autoScrollInterval) clearInterval(autoScrollInterval);
        }

        init();
    };
});
