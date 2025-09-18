
document.addEventListener("DOMContentLoaded", function(event) {
    console.log("DOM loaded");

    window.addEventListener("load", function(e) {
        // Animate elements with the class .logo-home
        gsap.to('.logo-image-home', {
            rotation: 360,
            duration: 1,
            ease: 'bounce.out',
        });

        // Animate elements with the class .box
        gsap.to('.box', {
            rotation: 360,
            duration: 1,
            ease: 'bounce.out',
        });

        console.log("Window loaded");
    }, false);
});