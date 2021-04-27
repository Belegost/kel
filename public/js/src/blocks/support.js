var supportBlock = (function(){
    return animatedBlocksParam('.support-block');
})();

window.addEventListener('scroll', function () {
    var scrolled = window.pageYOffset || document.documentElement.scrollTop;
    if(supportBlock.section&&windowWidth>767) {
        supportBlock.sectionBg.style.transform = "translateY(" + 0.25 * (scrolled - supportBlock.sectionOffset) + "px)";
    }
});
