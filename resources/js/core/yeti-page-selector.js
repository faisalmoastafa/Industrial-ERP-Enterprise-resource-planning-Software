document.addEventListener('DOMContentLoaded', () => {
    // Register MorphSVGPlugin for GSAP 3
    if (typeof gsap !== 'undefined' && typeof MorphSVGPlugin !== 'undefined') {
        gsap.registerPlugin(MorphSVGPlugin);
    }

    const handSVG = document.getElementById('hand-svg');
    if (!handSVG) return;

    const fingerBG = document.getElementById('finger-bg');
    const fingerBorder = document.getElementById('finger-border');
    const fingernail1 = document.getElementById('fingernail-1');
    const fingernail2 = document.getElementById('fingernail-2');

    let TL = null;

    // Morphed shapes when hand is pointing
    const fingerBGB = "M320.8,119c-1-6.5-7-10.9-13.5-9.9l-0.1,0c-6.2,0.9-12.3,1.8-18.3,2.4c-6,0.6-11.8,1-17.6,1 c-5.8,0-11.6,0.1-17.4-0.9c-8-1.4-13.6-5.4-17.4-11c-3.1-4.5-5.1-10-6.3-15.9c-1-4.6-1.5-9.4-1.8-14.1c-0.4-6.4,0-12.8,1.5-19.1 c1.5-6.3,4.2-12.6,7.7-18.6l0.1-0.2c2.9-5,1.8-11.5-2.9-15.1c-5.1-4-12.4-3.1-16.4,2c-2.2,2.8-4.2,5.7-6.1,8.7 c-2.9,4.7-5.5,9.6-7.5,14.9c-3.4,8.7-5.5,18.1-5.9,27.5c-0.5,9.4,0.5,18.8,2.4,27.7c1.9,8.9,4.7,17.4,8.1,25.6l0,0.1 c0.3,0.7,0.6,1.4,1,2c1.5,2.7,3.6,4.8,6.1,6.4c2.3,7.2,17.1,12.4,17.1,12.4c6.7,0,13.4-0.1,20.2-0.5c6.8-0.4,13.6-1.1,20.4-2.3 c6.8-1.1,13.5-2.6,19.9-4.4c6.4-1.7,12.6-3.7,18.7-5.6C318.2,130.3,321.7,124.8,320.8,119z";
    const fingerBorderB = "M257.9 144.9c4.8-.4 11.5-2 16.3-2.8 6.8-1.1 13.5-2.6 19.9-4.4 6.4-1.7 12.6-3.7 18.7-5.6 5.4-1.7 9-7.2 8.1-13-1-6.5-7-10.9-13.5-9.9h-.1c-6.2.9-12.3 1.8-18.3 2.4-6 .6-11.8 1-17.6 1-5.8 0-11.6.1-17.4-.9-9.8-1.7-15.9-7.3-19.7-14.9-2.1-4.2-3.6-9.1-4.5-14.1-.7-3.9-1.2-8-1.4-12-.4-6.4 0-12.8 1.5-19.1 1.5-6.3 4.2-12.6 7.7-18.6l.1-.2c2.9-5 1.8-11.5-2.9-15.1-5.1-4-12.4-3.1-16.4 2-1.4 1.7-2.7 3.5-4 5.4-3.8 5.6-7.1 11.7-9.7 18.2-3.4 8.7-5.5 18.1-5.9 27.5-.4 7.1.1 14.1 1.1 20.9";
    const fingernail2B = "M229.6 17.5l-5.8 11.2c-.6 1.1-1.9 1.5-2.9.9l-2.5-1.3c-1.1-.6-1.5-1.9-.9-2.9l2.7-5.2c1.8-3.4 6-4.7 9.3-2.9.1 0 .1.1.1.2z";
    const fingernail1B = "M303.2 128.9l-.4-1.7c-.5-2.3.9-4.5 3.1-5l12.3-2.8c1.2 5.2-2 10.3-7.2 11.5l-3.5.8c-1.9.4-3.9-.8-4.3-2.8z";

    // Set initial GSAP parameters for hand SVG
    gsap.set(handSVG, {
        autoAlpha: 0,
        y: 250,
        rotation: 0
    });

    // Event delegation for DataTable pagination clicks
    document.addEventListener('click', (e) => {
        const pageLink = e.target.closest('.pagination .page-link, .dataTables_paginate .page-link, .paginate_button a');
        if (!pageLink) return;

        // Skip disabled or active buttons
        const parentLi = pageLink.closest('li');
        if (parentLi && (parentLi.classList.contains('disabled') || parentLi.classList.contains('active'))) {
            return;
        }

        // Get coordinates of the clicked page link relative to the viewport
        const linkRect = pageLink.getBoundingClientRect();
        
        // svgWidth = 175px, pointing finger X is at 251 in 350 viewBox, so (251 / 350) * 175 = 125.5px
        const fingerXOffset = 125.5; 
        const targetLeft = linkRect.left + (linkRect.width / 2) - fingerXOffset;
        const targetTop = linkRect.bottom - 10;

        // Reset and position hand SVG at the bottom relative to the link
        if (TL && TL.isActive()) {
            TL.kill();
        }

        // Move hand SVG to match the horizontal center of the clicked button
        gsap.set(handSVG, {
            left: targetLeft,
            top: targetTop,
            bottom: 'auto',
            y: 250,
            autoAlpha: 1,
            display: 'block'
        });

        // Initialize Timeline
        TL = gsap.timeline({ paused: true });

        // Phase A Animation:
        // 1. Slide hand UP to point at the button
        // 2. Click effect / indicator transition occurs (simulated)
        // 3. Slide hand back DOWN off-screen and hide
        TL
            .to(handSVG, { duration: 0.4, y: 0, ease: "power2.out" }, 0)
            
            // Hold position for a brief moment to simulate the click reaction
            .to(handSVG, { duration: 0.15, y: 2, ease: "power1.inOut" }, 0.75)
            
            // Retreat back down
            .to(handSVG, { duration: 0.4, y: 250, ease: "power2.in" }, 0.9)
            .to(handSVG, { duration: 0.05, autoAlpha: 0, display: 'none' }, 1.3);

        TL.play();
    });
});
