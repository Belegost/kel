
var productsPage = (function(){
    return {
        bigDigit: document.querySelector('.productpage-increase-persent .big-digits'),
        trustStructureBarsHidden: true,
        trustStructureBars: document.querySelector('.productpage-truststructure-bars'),
        сryptoTrust: animatedBlocksParam('.productpage-cryptotrust')
    }
})();

var radialCrypto = radialIndicator('.indicator_crypto' , {
    barBgColor: '#dcf3f7',
    barColor: '#56b8d2',
    barWidth: 7,
    radius:65,
    fontSize: 32,
    roundCorner : false,
    percentage: true
});
var radialForex = radialIndicator('.indicator_forex' , {
    barBgColor: '#d5e3f6',
    barColor: '#2d64c1',
    barWidth: 7,
    radius:65,
    fontSize: 32,
    roundCorner : false,
    percentage: true
});
var radialCFD = radialIndicator('.indicator_cfd' , {
    barBgColor: '#c5ccd2',
    barColor: '#40556b',
    barWidth: 7,
    radius:65,
    fontSize: 32,
    roundCorner : false,
    percentage: true
});

window.addEventListener('load', function() {
});
window.addEventListener('scroll', function() {
    var scrolled = window.pageYOffset || document.documentElement.scrollTop;

    if(productsPage.bigDigit&&isVisible(productsPage.bigDigit)) {
        incrementDigits.init(".productpage-increase-persent .big-digits", {decimalPlaces:3,decimalPoint:".",duration: 1500});
    }
    if(productsPage.trustStructureBars&&isVisible(productsPage.trustStructureBars)&&productsPage.trustStructureBarsHidden) {
        productsPage.trustStructureBarsHidden = false;
        setTimeout(function () {
            radialCrypto.animate(78);
        }, 500);
        setTimeout(function () {
            radialForex.animate(73);
        }, 750);
        setTimeout(function () {
            radialCFD.animate(67);
        }, 1000);
    }
    if(windowWidth>767) {
        if(productsPage.сryptoTrust.section) productsPage.сryptoTrust.sectionBg.style.transform = "translateY(" + 0.25 * (scrolled - productsPage.сryptoTrust.sectionOffset) + "px)";
    }
});